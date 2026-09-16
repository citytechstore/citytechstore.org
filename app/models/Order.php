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
}

$OrderModel = new Order($DatabaseModel);
