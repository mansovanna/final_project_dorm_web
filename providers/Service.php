<?php

namespace Providers;

class Service
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($request)
    {
        $student_id = mysqli_real_escape_string($this->conn, $request['student_id'] ?? '');
        $password = mysqli_real_escape_string($this->conn, $request['password'] ?? '');

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

        // ✅ Get token (login on one device only)
        $tokenResponse = $this->token($user['student_id']);

        return json_encode([
            'success' => true,
            'message' => 'Login successful',
            'token' => $tokenResponse['token'],
            'expires' => $tokenResponse['expires'],
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
        $expires = time() + 3600;

        // Optional cleanup
        $this->conn->query("DELETE FROM tokens WHERE expires < " . time());

        $query = "
        INSERT INTO tokens (token, expires, user_id)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE token = VALUES(token), expires = VALUES(expires)
    ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'DB prepare failed: ' . $this->conn->error];
        }

        $stmt->bind_param("sii", $token, $expires, $user_id);
        $executed = $stmt->execute();

        if (!$executed) {
            return ['success' => false, 'message' => 'Token insert/update failed: ' . $stmt->error];
        }

        return ['success' => true, 'token' => $token, 'expires' => $expires];
    }


    public function validateToken($token)
    {
        $query = "SELECT * FROM tokens WHERE token = ? AND expires > ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return false;
        }

        $now = time();
        $stmt->bind_param("si", $token, $now);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }

        return false;
    }


    public function getUser () {
        // ------------------- if user is logged in -------------------
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        }

        list($type, $token) = explode(' ', $headers['Authorization'], 2);
        if (strcasecmp($type, 'Bearer') !== 0 || empty($token)) {
            return false;
        }

        return $this->validateToken($token);
    }


    public function user($user_id)
    {
        global $conn;
        $select = "SELECT * FROM register WHERE student_id = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        if ($user && isset($user['password'])) {
            unset($user['password']);
        }
        return $user;
    }
}
