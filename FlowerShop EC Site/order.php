<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'ゲスト';



$dsn = 'mysql:host=localhost;dbname=3cdn2214;charset=utf8';
$usernameDB = 'root';
$passwordDB = 'lin';
try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $pdo->prepare('SELECT phone, address FROM user WHERE id = :id');
    $query->execute(['id' => $_SESSION['user_id']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $address = htmlspecialchars($user['address']);
    $phone = htmlspecialchars($user['phone']);

} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}


$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];


if (empty($cartItems)) {
    header('Location: cart.php');
    exit();
}


function generateUniqueOrderId($pdo) {
    do {
        $orderId = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8); // 8位随机字符串
        $query = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE ordernumber = :ordernumber');
        $query->execute(['ordernumber' => $orderId]);
        $count = $query->fetchColumn();
    } while ($count > 0); 
    return $orderId;
}

$orderId = generateUniqueOrderId($pdo);

$userId = $_SESSION['user_id'];
$query = $pdo->prepare('SELECT name, phone, address FROM user WHERE id = :id');
$query->execute(['id' => $userId]);
$user = $query->fetch(PDO::FETCH_ASSOC);
$username = htmlspecialchars($user['name']);
$address = htmlspecialchars($user['address']);
$phone = htmlspecialchars($user['phone']);


$totalPrice = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    try {
        $pdo->beginTransaction();


        $queryOrder = $pdo->prepare('INSERT INTO orders (ordernumber, name, totalprice, phone, address) 
                                     VALUES (:ordernumber, :name, :totalprice, :phone, :address)');
        $queryOrder->execute([
            'ordernumber' => $orderId,
            'name' => $username,
            'totalprice' => $totalPrice,
            'phone' => $phone,
            'address' => $address
        ]);


        $queryItem = $pdo->prepare('INSERT INTO order_items (ordernumber, productname, price, quantity, totalprice) 
                                    VALUES (:ordernumber, :productname, :price, :quantity, :totalprice)');
        foreach ($cartItems as $productName => $item) {
            $queryItem->execute([
                'ordernumber' => $orderId,
                'productname' => htmlspecialchars($productName),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'totalprice' => $item['price'] * $item['quantity']
            ]);
            $totalPrice += $item['price'] * $item['quantity'];
        }


        $updateOrder = $pdo->prepare('UPDATE orders SET totalprice = :totalprice WHERE ordernumber = :ordernumber');
        $updateOrder->execute([
            'totalprice' => $totalPrice,
            'ordernumber' => $orderId
        ]);

        $pdo->commit();
        unset($_SESSION['cart']); 
        header('Location: shop.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die('注文処理中にエラーが発生しました: ' . $e->getMessage());
    }
}


foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文確認</title>
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

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-info {
            font-size: 18px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .buttons button {
            font-size: 16px;
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirm-button {
            background-color: #2ecc71;
            color: white;
        }

        .confirm-button:hover {
            background-color: #27ae60;
        }

        .return-button {
            font-size: 16px;
            padding: 10px 20px;
            margin: 0 10px;
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            line-height: normal;
        }

        .return-button:hover {
            background-color: #2980b9;
        }

    </style>

    <script>
        function confirmOrder() {
            return confirm('本当にこの注文を確定しますか？');
        }
    </script>
</head>
<body>
<header>注文確認</header>
<div class="container">
    <div class="order-info">
        <p>注文番号: <strong><?php echo $orderId; ?></strong></p>
        <p>ユーザー名: <strong><?php echo $username; ?></strong></p>
        <p>配送先: <strong><?php echo $address; ?></strong></p>
        <p>電話番号: <strong><?php echo $phone; ?></strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>単価</th>
                <th>数量</th>
                <th>価格合計</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cartItems as $productName => $item): ?>
            <tr>
                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($productName); ?>" width="50"></td>
                <td><?php echo htmlspecialchars($productName); ?></td>
                <td>¥<?php echo number_format($item['price']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>¥<?php echo number_format($item['price'] * $item['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        合計金額: ¥<?php echo number_format($totalPrice); ?>
    </div>

    <div class="buttons">
        <form method="post" onsubmit="return confirmOrder();">
            <button type="submit" name="confirm_order" class="confirm-button">注文確認</button>
        </form>
        <a href="cart.php" class="return-button">カートに戻る</a>
    </div>
</div>
</body>
</html>
