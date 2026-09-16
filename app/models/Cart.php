<?php
require_once('Database.php');

class Cart
{
    private $db;

    public function __construct($database_model)
    {
        $this->db = $database_model;
    }

    private function findItem($sessionId, $customerId, $productId)
    {
        if ($customerId !== null) {
            $sql = "SELECT * FROM cart_items WHERE customer_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $customerId, $productId);
        } else {
            $sql = "SELECT * FROM cart_items WHERE session_id = ? AND customer_id IS NULL AND product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $sessionId, $productId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addItem($sessionId, $customerId, $productId, $quantity)
    {
        $existing = $this->findItem($sessionId, $customerId, $productId);

        if ($existing) {
            $newQuantity = $existing['quantity'] + $quantity;
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $newQuantity, $existing['id']);
            return $stmt->execute();
        }

        // Enforce the schema's invariant: exactly one of customer_id /
        // session_id is set per row, regardless of what the caller passed.
        $insertSessionId = ($customerId !== null) ? null : $sessionId;

        $sql = "INSERT INTO cart_items (customer_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isii", $customerId, $insertSessionId, $productId, $quantity);
        return $stmt->execute();
    }

    public function getCartItems($sessionId, $customerId)
    {
        if ($customerId !== null) {
            $sql = "SELECT cart_items.id, cart_items.product_id, cart_items.quantity,
                           products.name, products.unit_price, products.product_picture_url
                    FROM cart_items
                    JOIN products ON products.id = cart_items.product_id
                    WHERE cart_items.customer_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $customerId);
        } else {
            $sql = "SELECT cart_items.id, cart_items.product_id, cart_items.quantity,
                           products.name, products.unit_price, products.product_picture_url
                    FROM cart_items
                    JOIN products ON products.id = cart_items.product_id
                    WHERE cart_items.session_id = ? AND cart_items.customer_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $sessionId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateQuantity($cartItemId, $sessionId, $customerId, $newQuantity)
    {
        if ($customerId !== null) {
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND customer_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iii", $newQuantity, $cartItemId, $customerId);
        } else {
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND session_id = ? AND customer_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iis", $newQuantity, $cartItemId, $sessionId);
        }
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function removeItem($cartItemId, $sessionId, $customerId)
    {
        if ($customerId !== null) {
            $sql = "DELETE FROM cart_items WHERE id = ? AND customer_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $cartItemId, $customerId);
        } else {
            $sql = "DELETE FROM cart_items WHERE id = ? AND session_id = ? AND customer_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("is", $cartItemId, $sessionId);
        }
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function clearCart($sessionId, $customerId)
    {
        if ($customerId !== null) {
            $sql = "DELETE FROM cart_items WHERE customer_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $customerId);
        } else {
            $sql = "DELETE FROM cart_items WHERE session_id = ? AND customer_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $sessionId);
        }
        return $stmt->execute();
    }
}

$CartModel = new Cart($DatabaseModel);
