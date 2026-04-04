<?php
session_start();


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username']; 

$dsn = 'mysql:host=localhost;dbname=3cdn2214;charset=utf8';
$usernameDB = 'root';
$passwordDB = 'lin';

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $query = $pdo->prepare('
        SELECT 
            o.ordernumber, 
            o.created_at, 
            o.totalprice AS order_total,
            i.productname, 
            i.price AS product_price, 
            i.quantity, 
            i.price * i.quantity AS product_total
        FROM orders o
        JOIN order_items i ON o.ordernumber = i.ordernumber
        WHERE o.name = :name
        ORDER BY o.created_at DESC
    ');
    $query->execute(['name' => $username]);
    $orderDetails = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注文履歴</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fff2e6, #f9f9f9);
        }

        header {
            text-align: center;
            background-color: #333;
            padding: 30px;
            color: white;
            font-size: 36px;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        .order-header {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }

    </style>
</head>
<body>
<header>注文履歴</header>
<div class="container">
    <h2>注文履歴詳細</h2>
    <?php if (count($orderDetails) > 0): ?>
        <?php 
        $currentOrderNumber = null; 
        foreach ($orderDetails as $detail): 
            if ($currentOrderNumber !== $detail['ordernumber']): 
                if ($currentOrderNumber !== null): ?>
                    </table>
                <?php endif; ?>
                <div class="order-header">
                    注文番号: <?php echo htmlspecialchars($detail['ordernumber']); ?><br>
                    注文日: <?php echo htmlspecialchars($detail['created_at']); ?><br>
                    合計金額: ¥<?php echo number_format($detail['order_total']); ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>数量</th>
                            <th>価格合計</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php 
                $currentOrderNumber = $detail['ordernumber']; 
            endif; ?>
                <tr>
                    <td><?php echo htmlspecialchars($detail['productname']); ?></td>
                    <td>¥<?php echo number_format($detail['product_price']); ?></td>
                    <td><?php echo $detail['quantity']; ?></td>
                    <td>¥<?php echo number_format($detail['product_total']); ?></td>
                </tr>
        <?php endforeach; ?>
                    </tbody>
                </table>
    <?php else: ?>
        <p>注文履歴はありません。</p>
    <?php endif; ?>
</div>
</body>
</html>
