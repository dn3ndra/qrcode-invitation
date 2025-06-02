<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row["password"])) {
            $_SESSION["loggedin"] = true;
            header("Location: scanner.php");
            exit;
        }
    }
    $error = "Login gagal!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login - Wedding Guestbook</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 0 15px;
        }
        
        .login-form {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            margin: 0;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
        }
        
        .login-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 16px;
            font-size: 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            background: #fff;
            transition: border-color 0.2s;
            min-height: 52px;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .form-button {
            width: 100%;
            padding: 16px 24px;
            font-size: 18px;
            font-weight: 600;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            min-height: 56px;
            margin-top: 10px;
        }
        
        .form-button:hover {
            background: #0056b3;
        }
        
        .form-button:active {
            transform: translateY(1px);
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 20px auto;
                padding: 0 10px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="post" class="login-form">
            <h2 class="login-title">🔐 Login Panitia</h2>
            
            <?php if (!empty($error)): ?>
                <div class="login-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username" class="form-label">Username:</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-input" 
                       required 
                       autocomplete="username"
                       placeholder="Masukkan username">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input" 
                       required 
                       autocomplete="current-password"
                       placeholder="Masukkan password">
            </div>
            
            <button type="submit" class="form-button">Login</button>
        </form>
    </div>
</body>
</html>