<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];  
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (!empty($name) && !empty($password) && !empty($confirmPassword) && !empty($phone) && !empty($address)) {
        
        
        if ($password !== $confirmPassword) {
            echo "<script>alert('パスワードが一致しません！');</script>";
        } else {
            $conn = new mysqli('localhost', 'root', 'lin', '3cdn2214');

            if ($conn->connect_error) {
                die('データベース接続失敗：' . $conn->connect_error);
            }

            $checkStmt = $conn->prepare('SELECT id FROM user WHERE name = ? AND password = ?');
            $checkStmt->bind_param('ss', $name, $password);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('このユーザーは既に存在します！');</script>";
            } else {
                $stmt = $conn->prepare('INSERT INTO user (name, password, phone, address) VALUES (?, ?, ?, ?)');
                if ($stmt) {
                    $stmt->bind_param('ssss', $name, $password, $phone, $address);
                    if ($stmt->execute()) {
                        echo "<script>alert('登録が完了しました！'); window.location.href='home.php';</script>";
                    } else {
                        error_log("SQL Error: " . $stmt->error);
                        echo "<script>alert('登録失敗：" . $stmt->error . "');</script>";
                    }
                    $stmt->close();
                } else {
                    error_log("SQL Error: " . $conn->error);
                    echo "<script>alert('登録失敗：SQLエラー');</script>";
                }
            }

            $checkStmt->close();
            $conn->close();
        }
    } else {
        echo "<script>alert('すべてのフィールドを入力してください！');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録ページ</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f9f9f9, #ffecd2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #2c3e50;
            font-size: 36px;
            font-weight: bold;
            margin-top: 20px;
        }

        form {
            display: inline-block;
            text-align: left;
            margin-top: 30px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        button {
            font-size: 16px;
            padding: 12px 30px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 50px;
            background-color: #ff6b81;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #ff4e66;
            transform: translateY(-3px);
        }

        .cancel-button {
            background-color: #d9534f;
        }

        .cancel-button:hover {
            background-color: #c9302c;
        }

    </style>
</head>
<body>

    <h1>新規会員登録</h1>
    <form method="POST">
        <div class="form-group">
            <label for="name">名前：</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="password">パスワード：</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">パスワード確認：</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label for="phone">電話番号：</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="address">住所：</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="buttons">
            <button type="submit">登録</button>
            <button type="button" class="cancel-button" onclick="window.location.href='home.php';">キャンセル</button>
        </div>
    </form>

</body>
</html>
