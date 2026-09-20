<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email dan password wajib diisi']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid']);
        exit;
    }

    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo json_encode(['status' => 'success', 'message' => 'Login berhasil']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email atau password salah']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan']);
}
