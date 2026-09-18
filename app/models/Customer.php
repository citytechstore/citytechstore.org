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
