<?php
session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$dsn = 'mysql:host=localhost;dbname=3cdn2214;charset=utf8';
$usernameDB = 'root';
$passwordDB = 'lin';

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
        $ordernumber = $_POST['ordernumber'];
        $pdo->beginTransaction();
        $pdo->prepare('DELETE FROM order_items WHERE ordernumber = :ordernumber')->execute(['ordernumber' => $ordernumber]);
        $pdo->prepare('DELETE FROM orders WHERE ordernumber = :ordernumber')->execute(['ordernumber' => $ordernumber]);
        $pdo->commit();
        echo "<script>alert('注文が削除されました。');window.location.href='admin_dashboard.php';</script>";
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
        $ordernumber = $_POST['ordernumber'];
        $column = $_POST['column'];
        $value = $_POST['value'];

        $pdo->prepare("UPDATE orders SET $column = :value WHERE ordernumber = :ordernumber")
            ->execute(['value' => $value, 'ordernumber' => $ordernumber]);
        echo "<script>alert('注文情報が更新されました。');window.location.href='admin_dashboard.php';</script>";
        exit();
    }


    $query = $pdo->query('
        SELECT 
            o.ordernumber, 
            o.created_at, 
            o.totalprice AS order_total,
            o.name AS customer_name,
            o.phone AS customer_phone,
            o.address AS customer_address,
            i.productname, 
            i.price AS product_price, 
            i.quantity, 
            i.totalprice AS product_total
        FROM orders o
        LEFT JOIN order_items i ON o.ordernumber = i.ordernumber
        ORDER BY o.created_at DESC
    ');
    $orders = $query->fetchAll(PDO::FETCH_ASSOC);

    $groupedOrders = [];
    foreach ($orders as $order) {
        $ordernumber = $order['ordernumber'];
        if (!isset($groupedOrders[$ordernumber])) {
            $groupedOrders[$ordernumber] = [
                'ordernumber' => $ordernumber,
                'created_at' => $order['created_at'],
                'customer_name' => $order['customer_name'],
                'customer_phone' => $order['customer_phone'],
                'customer_address' => $order['customer_address'],
                'order_total' => $order['order_total'],
                'items' => [],
            ];
        }
        $groupedOrders[$ordernumber]['items'][] = [
            'productname' => $order['productname'],
            'product_price' => $order['product_price'],
            'quantity' => $order['quantity'],
            'product_total' => $order['product_total'],
        ];
    }

} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文管理</title>
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
            padding: 30px;
            color: white;
            font-size: 36px;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .order {
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }

        .order-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .logout-container {
            text-align: center;
            margin-top: 20px;
        }

        .logout-container button {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-container button:hover {
            background-color: #c0392b;
        }

        .edit-input {
            width: 80%;
            padding: 5px;
        }

        .edit-buttons button {
            margin-left: 5px;
        }
    </style>
</head>
<body>
<header>注文管理</header>
<div class="container">
    <h2>全ての注文</h2>
    <?php if (count($groupedOrders) > 0): ?>
        <?php foreach ($groupedOrders as $order): ?>
            <div class="order">
                <div class="order-header">
                    注文番号: <?php echo htmlspecialchars($order['ordernumber']); ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="ordernumber" value="<?php echo htmlspecialchars($order['ordernumber']); ?>">
                        <button type="submit" name="delete_order" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">削除</button>
                    </form>
                    <br>
                    注文日: <?php echo htmlspecialchars($order['created_at']); ?><br>
                    顧客名:
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="ordernumber" value="<?php echo htmlspecialchars($order['ordernumber']); ?>">
                        <input type="hidden" name="column" value="name">
                        <input type="text" name="value" class="edit-input" value="<?php echo htmlspecialchars($order['customer_name']); ?>">
                        <button type="submit" name="update_order" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;">更新</button>
                    </form>
                    <br>
                    電話番号:
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="ordernumber" value="<?php echo htmlspecialchars($order['ordernumber']); ?>">
                        <input type="hidden" name="column" value="phone">
                        <input type="text" name="value" class="edit-input" pattern="\d*" maxlength="15" value="<?php echo htmlspecialchars($order['customer_phone']); ?>">
                        <button type="submit" name="update_order" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;">更新</button>
                    </form>
                    <br>
                    住所:
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="ordernumber" value="<?php echo htmlspecialchars($order['ordernumber']); ?>">
                        <input type="hidden" name="column" value="address">
                        <input type="text" name="value" class="edit-input" value="<?php echo htmlspecialchars($order['customer_address']); ?>">
                        <button type="submit" name="update_order" style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;">更新</button>
                    </form>
                    <br>
                    合計金額: ¥<?php echo number_format($order['order_total']); ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>数量</th>
                            <th>商品合計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['productname']); ?></td>
                                <td>¥<?php echo number_format($item['product_price']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>¥<?php echo number_format($item['product_total']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>注文はまだありません。</p>
    <?php endif; ?>
</div>
<div class="logout-container">
    <form action="admin_login.php" method="post">
        <button type="submit" name="logout">ログアウト</button>
    </form>
</div>
</body>
</html>
