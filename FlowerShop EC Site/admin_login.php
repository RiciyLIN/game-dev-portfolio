<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = 'ユーザー名またはパスワードが間違っています。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff2e6, #f9f9f9);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center; 
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px); 
            max-width: 280px; 
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: left; 
        }

        .login-container button {
            width: calc(100% - 20px); 
            max-width: 280px;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h2>管理者ログイン</h2>
        <form method="POST" action="admin_login.php">
            <input type="text" name="username" placeholder="ユーザー名" required>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit">ログイン</button>
        </form>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
