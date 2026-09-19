<?php
require_once('Database.php');

class Order
{
    private $db;

    public function __construct($database_model)
    {
        $this->db = $database_model;
    }

    public function createOrder($customerId, $addressData, $cartItems, $total)
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }
        $deliveryFee = 0.00;

        $this->db->beginTransaction();

        try {
            // Checkout doesn't yet save/reuse addresses — this insert only
            // exists to satisfy orders.address_id (NOT NULL FK).
            $addressSql = "INSERT INTO addresses (customer_id, label, full_address, city, state, phone_number, is_primary) VALUES (?, 'Home', ?, ?, ?, ?, 0)";
            $addressStmt = $this->db->prepare($addressSql);
            $addressStmt->bind_param(
                "issss",
                $customerId,
                $addressData['full_address'],
                $addressData['city'],
                $addressData['state'],
                $addressData['phone_number']
            );
            if (!$addressStmt->execute()) {
                throw new Exception('Failed to save delivery address.');
            }
            $addressId = $addressStmt->insert_id;

            // Insert with a temporary, guaranteed-unique placeholder so the
            // UNIQUE NOT NULL order_number constraint is satisfied immediately;
            // finalized below once we have the row's own auto-increment id.
            $tempOrderNumber = 'TEMP-' . bin2hex(random_bytes(8));
            $orderSql = "INSERT INTO orders (customer_id, order_number, address_id, subtotal, delivery_fee, total, status, payment_status, payment_reference) VALUES (?, ?, ?, ?, ?, ?, 'pending', 'pending', NULL)";
            $orderStmt = $this->db->prepare($orderSql);
            $orderStmt->bind_param(
                "isiddd",
                $customerId,
                $tempOrderNumber,
                $addressId,
                $subtotal,
                $deliveryFee,
                $total
            );
            if (!$orderStmt->execute()) {
                throw new Exception('Failed to create order.');
            }
            $orderId = $orderStmt->insert_id;

            $orderNumber = 'CTS-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);
            $updateSql = "UPDATE orders SET order_number = ? WHERE id = ?";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->bind_param("si", $orderNumber, $orderId);
            if (!$updateStmt->execute()) {
                throw new Exception('Failed to finalize order number.');
            }

            // Freeze each item's CURRENT unit price at the moment of purchase.
            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemSql);
            foreach ($cartItems as $item) {
                $productId = (int) $item['product_id'];
                $quantity = (int) $item['quantity'];
                $priceAtPurchase = (float) $item['unit_price'];
                $itemStmt->bind_param("iiid", $orderId, $productId, $quantity, $priceAtPurchase);
                if (!$itemStmt->execute()) {
                    throw new Exception('Failed to save order items.');
                }
            }

            $this->db->commit();

            return [
                'id' => $orderId,
                'order_number' => $orderNumber,
            ];
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // All orders for staff order management, newest first, with enough
    // customer info to identify who placed each one without a second query
    // per row.
    public function getAllOrders()
    {
        $sql = "SELECT orders.*,
                       customers.first_name AS customer_first_name,
                       customers.last_name AS customer_last_name,
                       customers.email AS customer_email
                FROM orders
                JOIN customers ON customers.id = orders.customer_id
                ORDER BY orders.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Same as getAllOrders() but also joins the delivery address, for the
    // staff Orders Management table + detail view — avoids an N+1
    // getOrderWithDetails() call per row. Kept separate from getAllOrders()
    // so that method's existing callers (e.g. the dashboard) never change.
    public function getAllOrdersWithDetails()
    {
        $sql = "SELECT orders.*,
                       customers.first_name AS customer_first_name,
                       customers.last_name AS customer_last_name,
                       customers.email AS customer_email,
                       customers.phone_number AS customer_phone_number,
                       addresses.label AS address_label,
                       addresses.full_address AS address_full_address,
                       addresses.city AS address_city,
                       addresses.state AS address_state,
                       addresses.phone_number AS address_phone_number
                FROM orders
                JOIN customers ON customers.id = orders.customer_id
                JOIN addresses ON addresses.id = orders.address_id
                ORDER BY orders.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Line items (with product name/thumbnail) for many orders in a single
    // query, grouped by order_id — the bulk counterpart to getOrderItems(),
    // for list/table views that would otherwise run one query per row.
    public function getOrderItemsForOrderIds(array $orderIds)
    {
        if (empty($orderIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
        $types = str_repeat('i', count($orderIds));

        $sql = "SELECT order_items.order_id, order_items.id, order_items.product_id, order_items.quantity, order_items.price_at_purchase,
                       products.name, products.product_picture_url
                FROM order_items
                JOIN products ON products.id = order_items.product_id
                WHERE order_items.order_id IN ($placeholders)
                ORDER BY order_items.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$orderIds);
        $stmt->execute();
        $result = $stmt->get_result();

        $itemsByOrderId = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $itemsByOrderId[(int) $row['order_id']][] = $row;
        }
        return $itemsByOrderId;
    }

    // All orders belonging to one customer, newest first, for the
    // customer-facing "My Orders" list.
    public function getOrdersByCustomerId($customerId)
    {
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Single order with customer contact info and delivery address, for the
    // staff order-detail view. Kept separate from getOrderById() (used by
    // the live checkout/payment flow) so that flow's simple orders.* shape
    // never changes.
    public function getOrderWithDetails($orderId)
    {
        $sql = "SELECT orders.*,
                       customers.first_name AS customer_first_name,
                       customers.last_name AS customer_last_name,
                       customers.email AS customer_email,
                       customers.phone_number AS customer_phone_number,
                       addresses.label AS address_label,
                       addresses.full_address AS address_full_address,
                       addresses.city AS address_city,
                       addresses.state AS address_state,
                       addresses.phone_number AS address_phone_number
                FROM orders
                JOIN customers ON customers.id = orders.customer_id
                JOIN addresses ON addresses.id = orders.address_id
                WHERE orders.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // One customer's orders with their delivery address joined, for the
    // Customers page detail section — no customers join needed since the
    // caller already has that customer's own record.
    public function getOrdersWithAddressByCustomerId($customerId)
    {
        $sql = "SELECT orders.*,
                       addresses.label AS address_label,
                       addresses.full_address AS address_full_address,
                       addresses.city AS address_city,
                       addresses.state AS address_state,
                       addresses.phone_number AS address_phone_number
                FROM orders
                JOIN addresses ON addresses.id = orders.address_id
                WHERE orders.customer_id = ?
                ORDER BY orders.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Paid spend per calendar month for one customer, for the Customers
    // page's spend-over-time chart. Only returns rows for months that
    // actually had a paid order — the caller zero-fills the rest, same
    // pattern as getDailyRevenueForLastNDays().
    public function getMonthlySpendForCustomer($customerId, $months = 12)
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total) AS spend
                FROM orders
                WHERE customer_id = ? AND payment_status = 'paid'
                  AND created_at >= (CURDATE() - INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $customerId, $months);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // $newStatus is checked against the exact orders.status ENUM values —
    // never interpolated or bound as an arbitrary caller-supplied string.
    public function updateOrderStatus($orderId, $newStatus)
    {
        $allowedStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses, true)) {
            throw new Exception('Invalid order status.');
        }

        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $newStatus, $orderId);
        return $stmt->execute();
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT order_items.id, order_items.product_id, order_items.quantity, order_items.price_at_purchase,
                       products.name, products.product_picture_url
                FROM order_items
                JOIN products ON products.id = order_items.product_id
                WHERE order_items.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderByPaymentReference($paymentReference)
    {
        $sql = "SELECT * FROM orders WHERE payment_reference = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $paymentReference);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function markAsPaid($orderId, $paymentReference)
    {
        $sql = "UPDATE orders SET payment_status = 'paid', status = 'confirmed', payment_reference = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $paymentReference, $orderId);
        return $stmt->execute();
    }

    // Dashboard stat card: real money actually collected, not the face
    // value of unpaid/abandoned orders.
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(total) AS revenue FROM orders WHERE payment_status = 'paid'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return (float) ($row['revenue'] ?? 0);
    }

    public function getTotalOrdersCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM orders";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    // Paid revenue grouped by calendar day for the dashboard chart. Only
    // returns rows for days that actually had a paid order — the caller
    // fills in the missing days as zero so the chart shows real gaps
    // instead of silently omitting slow days.
    public function getDailyRevenueForLastNDays($days = 30)
    {
        $sql = "SELECT DATE(created_at) AS day, SUM(total) AS revenue
                FROM orders
                WHERE payment_status = 'paid' AND created_at >= (CURDATE() - INTERVAL ? DAY)
                GROUP BY DATE(created_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $days);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Top products by units sold, counting only paid orders — the same
    // "real completed sale" definition used for getTotalRevenue().
    public function getBestsellers($limit = 10)
    {
        $sql = "SELECT products.id, products.name, products.product_picture_url,
                       SUM(order_items.quantity) AS units_sold,
                       SUM(order_items.quantity * order_items.price_at_purchase) AS revenue
                FROM order_items
                JOIN orders ON orders.id = order_items.order_id
                JOIN products ON products.id = order_items.product_id
                WHERE orders.payment_status = 'paid'
                GROUP BY products.id, products.name, products.product_picture_url
                ORDER BY units_sold DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Dashboard "needs attention" card: orders not yet even confirmed.
    public function getPendingOrdersCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    // Paid revenue within a half-open [start, end) window, for the dashboard
    // trend badges (current 30 days vs. the prior 30 days).
    public function getRevenueBetween($start, $end)
    {
        $sql = "SELECT SUM(total) AS revenue FROM orders WHERE payment_status = 'paid' AND created_at >= ? AND created_at < ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float) ($row['revenue'] ?? 0);
    }

    // All orders (any status) within a half-open [start, end) window, for
    // the Total Orders trend badge.
    public function getOrdersCountBetween($start, $end)
    {
        $sql = "SELECT COUNT(*) AS total FROM orders WHERE created_at >= ? AND created_at < ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    // Dashboard's/Orders page's revenue totals are order-level (orders.total,
    // which includes delivery fee). This is the Products page's own
    // definition: pure product-attributable revenue from paid orders'
    // line items, excluding delivery fees — so it can legitimately differ
    // slightly from getTotalRevenue(). Units sold uses the same paid-only
    // rule as getBestsellers().
    public function getProductRevenueAndUnitsSold()
    {
        $sql = "SELECT SUM(order_items.quantity * order_items.price_at_purchase) AS revenue,
                       SUM(order_items.quantity) AS units_sold
                FROM order_items
                JOIN orders ON orders.id = order_items.order_id
                WHERE orders.payment_status = 'paid'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return [
            'revenue' => (float) ($row['revenue'] ?? 0),
            'units_sold' => (int) ($row['units_sold'] ?? 0),
        ];
    }
}

$OrderModel = new Order($DatabaseModel);
