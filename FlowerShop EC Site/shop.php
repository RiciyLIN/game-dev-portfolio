<?php
session_start();

if (isset($_GET['logout'])) {
  
    session_unset();
    session_destroy();
  
    header("Location: home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['product_name'];
    $productPrice = floatval($_POST['product_price']);
    $productImage = $_POST['product_image'];
    $quantity = intval($_POST['quantity']);


    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }


    if (isset($_SESSION['cart'][$productName])) {
        $_SESSION['cart'][$productName]['quantity'] += $quantity;
        $_SESSION['cart'][$productName]['total_price'] = $_SESSION['cart'][$productName]['price'] * $_SESSION['cart'][$productName]['quantity'];
    } else {
      
        $_SESSION['cart'][$productName] = [
            'price' => $productPrice,
            'image' => $productImage,
            'quantity' => $quantity,
            'total_price' => $productPrice * $quantity,
        ];
    }

   
    header('Location: cart.php');
    exit;
}


session_start(); 


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
    <title>LuXY florals</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 36px;
            letter-spacing: 2px; 
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        .category-container {
            padding: 10px;
            margin: 0 15%;
        }

        .category-container span {
            margin-right: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .category-controls {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .category-controls select {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
        }

        .product-region {
            padding: 30px 15%;
            margin-bottom: 100px;
            position: relative;
        }

        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 1px;
            color: #333;
            text-transform: uppercase;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.7);
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 30px;
            grid-auto-rows: 300px;
            row-gap: 100px;
            margin-top: 50px;
        }

        .product-frame {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            background: linear-gradient(135deg, #fff2e6, #f9f9f9);
            padding: 20px;
            border: 3px solid #333;
            border-bottom-width: 4px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 8px;
            height: 100%;
            position: relative;
        }

        .product-frame:hover {
            transform: translateY(-10px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product-frame img {
            width: 100%;
            height: auto;
            max-height: 70%;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-name, .product-price {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .product-price {
            color: #e67e22;
        }

        .logout-container, .cart-container {
            position: fixed;
            top: 10px;
            z-index: 1000; 
        }

        .logout-container {
            right: 100px;
        }

        .logout-container button, .cart-container button {
            padding: 10px 20px;
            background-color: #e74c3c; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .logout-container button:hover {
            background-color: #c0392b; 
        }

        .cart-container {
            right: 10px; 
        }

        .cart-container button {
            background-color: #3498db; 
        }

        .cart-container button:hover {
            background-color: #2980b9; 
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .modal-content button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-button {
            background-color: #e74c3c;
            color: white;
        }

        .add-to-cart {
            background-color: #2ecc71;
            color: white;

        }

        .history-container {
            position: fixed;
            top: 10px;
            right: 220px; 
            z-index: 1000;
        }
        .history-container button {
            padding: 10px 20px;
            background-color: #2ecc71; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .history-container button:hover {
            background-color: #27ae60; 
        }

    </style>
</head>
<body>

<!-- Logout -->
<div class="logout-container">
    <form action="shop.php" method="get">
        <button type="submit" name="logout">Logout</button>
    </form>
</div>

<!-- History -->
<div class="history-container">
    <form action="history.php" method="get">
        <button type="submit" name="history">履歴</button>
    </form>
</div>


<header>
    LuXY florals
</header>

<!-- Logout -->
<div class="logout-container">
    <form action="shop.php" method="get" onsubmit="return confirmLogout();">
        <button type="submit" name="logout">Logout</button>
    </form>
</div>

<script>
    function confirmLogout() {
        return confirm("ログアウトしますか？");
    }
</script>

<!-- Cart -->
<div class="cart-container">
    <form action="cart.php" method="get">
        <button type="submit" name="cart">Cart</button>
    </form>
</div>

<div class="user-info">
    ようこそ, <?php echo $username; ?>
</div>

<!-- 一 -->
<div class="product-region">
    <div class="product-title">温かな光を咲かせて</div>
    <div class="product-grid">
        <?php
        $image_files_1 = array("3.1", "3.2", "3.3", "3.4", "3.5", "3.6", "3.7", "3.8", "3.9", "3.10", "3.11", "3.12", "3.13", "3.14", "3.15");
        $prices_1 = [7000, 5700, 5800, 5700, 5800, 7900, 7000, 6900, 7600, 6900, 9700, 6000, 7000, 6900, 6700]; // 对应的价格
        foreach ($image_files_1 as $index => $image_file) {
            $flowerName = $image_file; 
            $price = $prices_1[$index]; 
            $stock = rand(5, 20);
            echo "
            <div class='product-frame' onclick=\"showModal('$image_file', '$flowerName', $price, $stock)\">
                <img src='img/$image_file.jpg' alt='Flower'>
                <div class='product-name'>$flowerName</div>
                <div class='product-price'>¥$price</div>
            </div>";
        }
        ?>
    </div>
</div>

<!-- pop-up -->
<div class="modal" id="product-modal">
    <div class="modal-content">
        <div class="modal-left">
            <img id="modal-image" src="" alt="Flower">
        </div>
        <div class="modal-right">
            <h2 id="modal-name"></h2>
            <p>価格: <span id="modal-price"></span></p>
            <form method="post" action="shop.php">
                <input type="hidden" name="product_name" id="modal-product-name">
                <input type="hidden" name="product_price" id="modal-product-price">
                <input type="hidden" name="product_image" id="modal-product-image">
                <label>
                    数量:
                    <button type="button" onclick="updateQuantity(1)">+</button>
                    <input type="number" id="modal-quantity" name="quantity" value="1" min="1">
                    <button type="button" onclick="updateQuantity(-1)">-</button>
                </label>
                <div>
                    <button type="submit" name="add_to_cart" class="add-to-cart">カートに追加</button>
                    <button type="button" class="close-button" onclick="closeModal()">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showModal(image, name, price) {
        document.getElementById('modal-image').src = 'img/' + image + '.jpg';
        document.getElementById('modal-name').textContent = name;
        document.getElementById('modal-price').textContent = '¥' + price;

        document.getElementById('modal-product-name').value = name;
        document.getElementById('modal-product-price').value = price;
        document.getElementById('modal-product-image').value = 'img/' + image + '.jpg';

        document.getElementById('product-modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('product-modal').style.display = 'none';
    }

    function updateQuantity(change) {
        const quantityInput = document.getElementById('modal-quantity');
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity + change > 0) {
            quantityInput.value = currentQuantity + change;
        }
    }

</script>


<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.7));
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.4s ease-out;
        z-index: 999;
    }
    .modal-content {
        display: flex;
        padding: 20px;
        background: linear-gradient(135deg, #fff2e6, #f9f9f9);
        border-radius: 20px;
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
        width: 700px;
        max-width: 90%;
        animation: modalSlideIn 0.5s ease-out;
        position: relative;
    }
    .modal-left {
        flex: 1.5;
        margin-right: 20px;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .modal-left img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        transition: transform 0.3s ease;
        display: block;
    }
    .modal-left img:hover {
        transform: scale(1.05);
    }
    .modal-right {
        flex: 2;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .modal-right h2 {
        font-size: 28px;
        color: #333;
        font-weight: 700;
        margin-bottom: 10px;
        animation: textAppear 0.6s ease-out;
    }
    .modal-right p {
        font-size: 18px;
        margin: 10px 0;
        color: #555;
    }
    .modal-right label {
        display: flex;
        align-items: center;
        margin-top: 10px;
        margin-left: 65px;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    .modal-right button {
        background: #FF6F61;
        border: none;
        color: white;
        font-size: 18px;
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .modal-right button:hover {
        background: #d85c4b;
        transform: translateY(-1px);
    }

    .modal-right input {
        width: 60px;
        margin: 0 10px;
        padding: 8px;
        text-align: center;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }
    .modal-right input:focus {
        border-color: #FF6F61;
        outline: none;
    }
    .add-to-cart, .close-button {
        background: #4CAF50;
        color: white;
        padding: 12px 20px;
        font-size: 18px;
        cursor: pointer;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
    }
    .add-to-cart:hover, .close-button:hover {
        background: #45a049;
        transform: scale(1.02);
    }
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes modalSlideIn {
        0% {
            transform: translateY(-30px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes textAppear {
        0% {
            opacity: 0;
            transform: translateX(-10px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
     input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>



<!-- 二 -->
<div class="product-region">
    <div class="product-title">太陽の色を追い求め</div>
    <div class="product-grid">
        <?php
        $image_files_2 = array("6.1", "6.2", "6.3", "6.4", "6.5", "6.6", "6.7", "6.8", "6.9", "6.10", "6.11", "6.12", "6.13", "6.14", "6.15", "6.16", "6.17");
        $prices_2 = [5800, 7600, 5800, 9700, 7900, 6000, 7600, 9700, 6000, 7000, 6900, 6700, 5700, 9700, 8500, 7000, 7900]; // 对应的价格
        foreach ($image_files_2 as $index => $image_file) {
            $flowerName = $image_file;
            $price = $prices_2[$index];
            $stock = rand(5, 20);
            echo "
            <div class='product-frame' onclick=\"showModal('$image_file', '$flowerName', $price, $stock)\">
                <img src='img/$image_file.jpg' alt='Flower'>
                <div class='product-name'>$flowerName</div>
                <div class='product-price'>¥$price</div>
            </div>";
        }
        ?>
    </div>
</div>

<!-- 三 -->
<div class="product-region">
    <div class="product-title">豊かに織り成す調和</div>
    <div class="product-grid">
        <?php
        $image_files_3 = array("9.1", "9.2", "9.3", "9.4", "9.5", "9.6", "9.7", "9.8", "9.9", "9.10", "9.11", "9.12", "9.13", "9.14", "9.15");
        $prices_3 = [7600, 9700, 6000, 7000, 6900, 6700, 5700, 7900, 7600, 9700, 8500, 6000, 7000, 7000, 5800]; // 对应的价格
        foreach ($image_files_3 as $index => $image_file) {
            $flowerName = $image_file;
            $price = $prices_3[$index];
            $stock = rand(5, 20);
            echo "
            <div class='product-frame' onclick=\"showModal('$image_file', '$flowerName', $price, $stock)\">
                <img src='img/$image_file.jpg' alt='Flower'>
                <div class='product-name'>$flowerName</div>
                <div class='product-price'>¥$price</div>
            </div>";
        }
        ?>
    </div>
</div>

<!-- 四 -->
<div class="product-region">
    <div class="product-title">色が寒さを打ち勝つ</div>
    <div class="product-grid">
        <?php
        $image_files_4 = array("12.1", "12.2", "12.3", "12.4", "12.5", "12.6", "12.7", "12.8", "12.9", "12.10", "12.11", "12.12", "12.13", "12.14", "12.15", "12.16");
        $prices_4 = [5700, 5800, 7900, 7900, 7000, 8500, 6000, 7000, 7000, 5800, 5700, 7900, 7600, 5700, 5800, 7900]; // 对应的价格
        foreach ($image_files_4 as $index => $image_file) {
            $flowerName = $image_file;
            $price = $prices_4[$index];
            $stock = rand(5, 20);
            echo "
            <div class='product-frame' onclick=\"showModal('$image_file', '$flowerName', $price, $stock)\">
                <img src='img/$image_file.jpg' alt='Flower'>
                <div class='product-name'>$flowerName</div>
                <div class='product-price'>¥$price</div>
            </div>";
        }
        ?>
    </div>
</div>
</body>
</html>
