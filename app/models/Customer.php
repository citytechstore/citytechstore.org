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

        if (!$customer || !password_verify($password, $customer['password'])) {
            throw new Exception('Invalid email or password.');
        }

        return $customer;
    }
}

$CustomerModel = new Customer($DatabaseModel);
