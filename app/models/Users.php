<?php
require_once('Database.php');

class Users
{
    private $db;

    public function __construct($database_model)
    {
        $this->db = $database_model;
    }

    // Method to add a new user
    public function createUser($user_arr)
    {
        // Check if the username, email, or phone number already exists
        if ($this->userExists($user_arr['username'], $user_arr['email'], $user_arr['phone_number'])) {
            throw new Exception('Username, email, or phone number already exists.');
            // return false;
        }

        // Proceed with adding the user if no conflicts found
       if($this->db->array_insert('users', $user_arr)){
           return true;
       }else{
        return false;
       }
    }
    public function createUserLastTimeDEv($user_arr)
    {
        // Check if the username, email, or phone number already exists
        if ($this->userExists($user_arr['username'], $user_arr['email'], $user_arr['phone_number'])) {
            throw new Exception('Username, email, or phone number already exists.');
        }
    
        // Proceed with adding the user if no conflicts found
        if ($this->db->array_insert('users', $user_arr)) {
            return true;
        } else {
            error_log("Failed to insert user: " . print_r($user_arr, true)); // Add this line for debugging
            return false;
        }
    }
    
    // Method to check if username, email, or phone number already exists
    public function userExists($username, $email, $phone_number)
    {
        $query = "SELECT * FROM users WHERE username = ? OR email = ? OR phone_number = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $email, $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // If any records are returned, it means a conflict exists
        return $result->num_rows > 0;
    }

    // Method to get a user by username
    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Method to get all users
    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Other methods like updateUser, deleteUser, etc.
    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete user");
        }
    }

    public function updateUser($user_id, $user_arr)
    {
        try {
            $this->db->array_update('users', $user_arr, ['id' => $user_id]);
        } catch (Exception $e) {
            throw new Exception("Failed to update user: " . $e->getMessage());
        }
    }

    public function loginUser_dev($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                throw new Exception("Invalid password");
            }
        } else {
            throw new Exception("User not found");
        }
    }
    public function loginUser($identifier, $password)
    {
        // Updated SQL to check username, email, or phone_number
        $sql = "SELECT * FROM users WHERE username = ? OR email = ? OR phone_number = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $identifier, $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                throw new Exception("Invalid password");
            }
        } else {
            throw new Exception("User not found");
        }
    }
    public function checkUserExistsWithSameRole($username, $email, $role) {
        $count = null;
        $sql = "SELECT COUNT(*) AS count FROM users WHERE (username = ? OR email = ?) AND role = ?";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param("sss", $username, $email, $role);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            // Error preparing the statement
            return null;
        }
    }
}


$UsersModel = new Users($DatabaseModel);
// $UsersModel->createUser([
//     'username' => 'admin',
//     'password' => password_hash('', PASSWORD_DEFAULT),
//     'email' => 'admin@gmail.com',
//     'firstname' => 'admin',
//     'lastname' =>  'user',
//     'phone_number' => '09121302484',
//     'role' => 'admin',
//     'profile_picture' => '',
//     'created_at' => date('Y-m-d H-i-s')
// ]);
