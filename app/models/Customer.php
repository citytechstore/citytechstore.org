<?php
require_once('Database.php');

class Customer
{
    private $db;

    public function __construct($database_model)
    {
        $this->db = $database_model;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM customers WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM customers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getCustomerCount()
    {
        $sql = "SELECT COUNT(*) AS total FROM customers";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    // Customers created within a half-open [start, end) window, for the
    // dashboard's Total Customers trend badge ("+N this period").
    public function getCustomerCountBetween($start, $end)
    {
        $sql = "SELECT COUNT(*) AS total FROM customers WHERE created_at >= ? AND created_at < ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $start, $end);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }

    // Explicit-column profile fetch for the staff Customers page — never
    // selects password, and resolves google_id to a boolean here so the
    // raw id value never leaves the database row.
    public function getPublicProfileById($id)
    {
        $sql = "SELECT id, first_name, last_name, email, phone_number,
                       google_id IS NOT NULL AS uses_google, created_at
                FROM customers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Falls back to the most recently added address when none is flagged
    // primary — for the Customers detail page's "Location" field.
    public function getPrimaryAddress($customerId)
    {
        $sql = "SELECT city, state FROM addresses
                WHERE customer_id = ?
                ORDER BY is_primary DESC, created_at DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // One aggregate query for the Customers list page: order count and paid
    // total per customer via LEFT JOIN + GROUP BY, no per-row queries.
    // Never selects password; google_id is only ever exposed as a boolean.
    public function getCustomerListWithStats()
    {
        $sql = "SELECT customers.id, customers.first_name, customers.last_name,
                       customers.email, customers.phone_number,
                       customers.google_id IS NOT NULL AS uses_google,
                       customers.created_at,
                       COUNT(orders.id) AS orders_count,
                       COALESCE(SUM(CASE WHEN orders.payment_status = 'paid' THEN orders.total ELSE 0 END), 0) AS total_spent
                FROM customers
                LEFT JOIN orders ON orders.customer_id = customers.id
                GROUP BY customers.id, customers.first_name, customers.last_name,
                         customers.email, customers.phone_number, customers.google_id, customers.created_at
                ORDER BY customers.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function register($data)
    {
        if ($this->findByEmail($data['email'])) {
            throw new Exception('An account with this email already exists.');
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO customers (first_name, last_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Failed to prepare registration statement.');
        }
        $stmt->bind_param(
            "sssss",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $hashedPassword,
            $data['phone_number']
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to create account.');
        }

        return $this->getById($stmt->insert_id);
    }

    public function login($email, $password)
    {
        $customer = $this->findByEmail($email);

        if (!$customer) {
            throw new Exception('Invalid email or password.');
        }

        // Explicit, auditable guard: a Google-only account's password is a
        // hash of a random value nobody knows, so password_verify() would
        // already always fail — this check makes that refusal provable
        // rather than incidental, and gives a clearer message.
        if (!empty($customer['google_id'])) {
            throw new Exception('This account uses Google Sign-In. Please use the "Sign in with Google" button instead.');
        }

        if (!password_verify($password, $customer['password'])) {
            throw new Exception('Invalid email or password.');
        }

        return $customer;
    }

    public function registerWithGoogle($data)
    {
        if ($this->findByEmail($data['email'])) {
            throw new Exception('An account with this email already exists.');
        }

        // No password is ever set by the customer for a Google-only account.
        // customers.password stays NOT NULL, so a hash of a random,
        // never-disclosed value is stored instead — login()'s google_id
        // guard above means this hash is never actually checked in practice.
        $placeholderPassword = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT);

        $sql = "INSERT INTO customers (first_name, last_name, email, password, google_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Failed to prepare registration statement.');
        }
        $stmt->bind_param(
            "sssss",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $placeholderPassword,
            $data['google_id']
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to create account.');
        }

        return $this->getById($stmt->insert_id);
    }
}

$CustomerModel = new Customer($DatabaseModel);
