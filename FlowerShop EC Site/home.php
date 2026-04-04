<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>花店ウェルカムページ</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #fff2e6, #f9f9f9); 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        h1 {
            color: #2c3e50;
            font-size: 36px;
            font-weight: bold;
            margin-top: 20px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 600px;
            overflow: hidden;
            z-index: -1;
        }

        .header img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 20px;
        }

        .logo {
            width: 250px;
            height: auto;
            margin-right: 20px;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .slogan {
            font-size: 50px;
            color: rgb(210, 84, 84);
            font-style: italic;
            text-transform: uppercase;
            font-weight: bold;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        
        .welcome-text {
            font-size: 18px;
            color: #333;
            margin-top: 40px;
            line-height: 1.8;
            text-align: left;
            font-family: 'Georgia', serif;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            letter-spacing: 1px;
        }

        .buttons {
            display: flex;
            flex-direction: row; 
            justify-content: center; 
            gap: 20px; 
            z-index: 2;
            margin-top: 30px;
        }

        button {
            font-size: 16px;
            padding: 12px 30px;
            cursor: pointer;
            border: none;
            border-radius: 50px;
            background-color: #ff6b81;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            width: 200px;
        }

        button:hover {
            background-color: #ff4e66;
            transform: translateY(-3px);
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }

        .footer a {
            color: #ff6b81;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- GIF -->
    <div class="header">
        <img src="xx.gif" alt="背景GIF">
    </div>

    <div class="content">
        <div class="logo-container">
            <img src="logo.png" alt="花店ロゴ" class="logo">
            <div class="slogan">花の香りに包まれる幸せな日々</div>
        </div>

        <div class="welcome-text">
            いらっしゃいませ<br><br>
            xx県xx市にございますLuXY floralsもとです。<br>
            この度はご来店ありがとうございます。<br><br>
            毎日新鮮なお花をご用意し、丁寧に制作させていただきます。<br>
            どうぞごゆっくりとご覧くださいませ。<br>
            ご不明な点等ございましたらお気軽にお問い合わせください。<br><br>

            営業時間　 8：00～19：00<br>
            定休日　　なし<br>
            電話番号　0123-12-1234<br>
            メール　　luxyflorals@gmail.com
        </div>

        <div class="buttons">
            <button onclick="window.location.href='signin.php';">新規会員登録</button>
            <button onclick="window.location.href='login.php';">会員ログイン</button>
        </div>
    </div>

</body>
</html>