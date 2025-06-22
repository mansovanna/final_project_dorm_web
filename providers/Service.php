<?php

namespace Providers;

class Service
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($input)
    {
        $student_id = mysqli_real_escape_string($this->conn, $input['student_id'] ?? '');
        $password = mysqli_real_escape_string($this->conn, $input['password'] ?? '');

        if (empty($student_id) || empty($password)) {
            http_response_code(400);
            return json_encode([
                'success' => false,
                'message' => 'Student ID and password are required.'
            ]);
        }

        $query = "SELECT * FROM register WHERE student_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            http_response_code(400);
            return json_encode([
                'success' => false,
                'message' => 'Invalid student ID'
            ]);
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            http_response_code(401);
            return json_encode([
                'success' => false,
                'message' => 'Invalid password'
            ]);
        }

        // ✅ Use correct user_id for tokens table
        if (!isset($user['user_id'])) {
            http_response_code(500);
            return json_encode([
                'success' => false,
                'message' => 'User ID missing from user record.'
            ]);
        }

        $tokenResponse = $this->token($user['user_id']);

        if (!$tokenResponse['success']) {
            http_response_code(500);
            return json_encode($tokenResponse);
        }

        return json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => $tokenResponse['token'],
            'expires_at' => $tokenResponse['expires_at'],
            'user' => [
                'student_id' => $user['student_id'],
                'username' => $user['username'] ?? 'User',
                'img' => $user['img'] ?? 'img/user1.png'
            ]
        ]);
    }

    public function token($user_id)
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour later

        // Clean up expired tokens
        $this->conn->query("DELETE FROM tokens WHERE expires_at < NOW()");

        $query = "
            INSERT INTO tokens (token, expires_at, user_id)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)
        ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ["success" => false, "message" => "Prepare failed: {$this->conn->error}"];
        }

        $stmt->bind_param("ssi", $token, $expires, $user_id);
        $executed = $stmt->execute();

        if (!$executed) {
            return ["success" => false, "message" => "Token insert failed: {$stmt->error}"];
        }

        return ['success' => true, 'token' => $token, 'expires_at' => $expires];
    }

    public function validateToken($token)
    {
        $token = trim($token); // Remove whitespace

        $query = "
        SELECT 
            tokens.token,
            tokens.expires_at,
            register.user_id,
            register.student_id,
            register.username,
            register.img
        FROM tokens
        JOIN register ON tokens.user_id = register.user_id
        WHERE tokens.token = ? AND tokens.expires_at <= NOW()
    ";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            return $result->fetch_assoc(); // Includes user + token info
        }

        return false;
    }


    public function getUser()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        }

        [$type, $token] = explode(' ', $headers['Authorization'], 2);
        if (strcasecmp($type, 'Bearer') !== 0 || empty($token)) {
            return false;
        }

        return $this->validateToken($token);
    }

    public function user($user_id)
    {
        $select = "SELECT * FROM register WHERE user_id = ?";
        $stmt = $this->conn->prepare($select);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result?->fetch_assoc();

        if ($user && isset($user['password'])) {
            unset($user['password']);
        }

        return $user;
    }
}
