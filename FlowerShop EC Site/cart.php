<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

if (isset($_POST['remove'])) {
    $productName = $_POST['product_name'];
    if (isset($_SESSION['cart'][$productName])) {
        unset($_SESSION['cart'][$productName]);
    }
}

if (isset($_POST['update_quantity'])) {
    $productName = $_POST['product_name'];
    $newQuantity = intval($_POST['quantity']);
    if ($newQuantity > 0 && isset($_SESSION['cart'][$productName])) {
        $_SESSION['cart'][$productName]['quantity'] = $newQuantity;
        $_SESSION['cart'][$productName]['total_price'] = 
            $_SESSION['cart'][$productName]['price'] * $newQuantity;
    } elseif (isset($_SESSION['cart'][$productName])) {
        unset($_SESSION['cart'][$productName]); 
    }
}



$cartItems = $_SESSION['cart'];


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'ゲスト';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショッピングカート</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff2e6, #f9f9f9);
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            background-color: #333;
            padding: 20px;
            color: white;
            font-size: 24px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        input[type="number"] {
            font-size: 18px;
            padding: 5px;
            width: 80px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        .update-button {
            font-size: 16px;
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-button:hover {
            background-color: #2980b9;
        }

        .remove-button {
            background-color: #e74c3c;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .remove-button:hover {
            background-color: #c0392b;
        }

        .cart-summary {
            width: 80%;
            margin: 20px auto;
            text-align: right;
        }

        .cart-summary span {
            font-size: 18px;
            font-weight: bold;
        }

        .buttons {
            text-align: center;
            margin: 20px auto;
        }

        .buttons button, .buttons a {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .confirm-button, .back-button {
            background-color: #2ecc71; 
            color: white;
            font-size: 16px; 
            padding: 10px 20px; 
            border: none;
            border-radius: 5px; 
            cursor: pointer;
            text-decoration: none; 
            display: inline-block; 
            text-align: center;
        }

        .confirm-button:hover {
            background-color: #27ae60; 
        }

        .back-button {
            background-color: #3498db;
        }

        .back-button:hover {
            background-color: #2980b9; 
        }

        .user-info {
            position: fixed;
            top: 10px;
            left: 20px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            z-index: 1000;
        }

    </style>
    <script>
        let confirmed = false;

        window.onbeforeunload = function () {
            if (!confirmed) {
                return '確認ボタンを押していません。リフレッシュしてもよろしいですか？';
            }
        };

        function confirmAction() {
            if (confirm('本当に購入しますか？')) {
                confirmed = true;
                alert('注文が確認されました！');
                window.location.href = 'order.php'; 
            }
        }

        function clearCart() {
            if (confirm('カート内の商品をすべてクリアしますか？')) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "clear_cart.php", true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        location.reload(); 
                    }
                };
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("action=clear_cart");
            }
        }

        function confirmRemove(productName) {
            return confirm(productName + ' を削除してもよろしいですか？');
        }
    </script>
</head>
<body>
<header>ショッピングカート</header>

<table>
    <thead>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>単価</th>
            <th>数量</th>
            <th>価格合計</th>
            <th>delete</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($cartItems)): ?>
            <tr>
                <td colspan="6">カートは空です。</td>
            </tr>
        <?php else: ?>
            <?php foreach ($cartItems as $productName => $item): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($productName); ?>" width="50"></td>
                    <td><?php echo htmlspecialchars($productName); ?></td>
                    <td>¥<?php echo number_format($item['price']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($productName); ?>">
                            <button type="submit" name="update_quantity" class="update-button">更新</button>
                        </form>
                    </td>
                    <td>¥<?php echo number_format($item['total_price']); ?></td>
                    <td>
                        <form method="post" style="display:inline;" onsubmit="return confirmRemove('<?php echo htmlspecialchars($productName); ?>');">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($productName); ?>">
                            <button type="submit" name="remove" class="remove-button">delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="cart-summary">
    <span>合計: ¥<?php 
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['total_price']; 
        }
        echo number_format($total);
    ?></span>
</div>


<div class="user-info">
    ようこそ, <?php echo $username; ?>
</div>

<div class="buttons">
    <button class="confirm-button" onclick="confirmAction()">買入</button>
    <a href="shop.php" class="back-button">ショップに戻る</a>
</div>

</body>
</html>
