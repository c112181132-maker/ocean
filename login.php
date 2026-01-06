<?php
session_start();
require_once 'db.php';

// 自動校準邏輯 (保留你之前成功的邏輯)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];
    $p = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$u]);
    $user = $stmt->fetch();

    if ($u === 'admin') {
        if (!$user) {
            $new_hash = password_hash('1234', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)")->execute(['admin', $new_hash]);
            $msg = "系統已初始化 admin 帳號";
        } elseif (!password_verify($p, $user['password'])) {
            $new_hash = password_hash('1234', PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = ? WHERE username = ?")->execute([$new_hash, 'admin']);
            $msg = "密碼已校準，請再次登入";
        } else {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        }
    } else {
        $msg = "帳號錯誤";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>DeepSea - 探測員登入</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', 微軟正黑體, sans-serif;
            background: radial-gradient(circle at center, #003d5b 0%, #001219 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* 裝飾用的深海氣泡效果 */
        .ocean-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
            width: 320px;
            text-align: center;
        }

        h2 {
            color: #94d2bd;
            margin-bottom: 30px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-size: 1.5rem;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            color: #a9d6e5;
            font-size: 0.8rem;
            display: block;
            margin-bottom: 5px;
            margin-left: 5px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid #0a9396;
            border-radius: 10px;
            color: #fff;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #94d2bd;
            box-shadow: 0 0 10px rgba(148, 210, 189, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: #0a9396;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            letter-spacing: 1px;
        }

        button:hover {
            background: #94d2bd;
            color: #001219;
            transform: translateY(-2px);
        }

        .message {
            margin-top: 20px;
            font-size: 0.85rem;
            color: #ee9b00;
        }

        /* 裝飾圓點 */
        .dot {
            position: absolute;
            background: rgba(148, 210, 189, 0.1);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>
<body>

    <div class="dot" style="width: 200px; height: 200px; top: -50px; left: -50px;"></div>
    <div class="dot" style="width: 150px; height: 150px; bottom: -30px; right: -20px;"></div>

    <div class="login-card">
        <h2>DEEP SEA OPS</h2>
        <form method="POST">
            <div class="input-group">
                <label>OPERATOR ID</label>
                <input type="text" name="username" placeholder="Username" required autocomplete="off">
            </div>
            <div class="input-group">
                <label>ACCESS CODE</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">驗證身分並啟動系統</button>
        </form>
        
        <?php if(isset($msg)): ?>
            <div class="message"><?= $msg ?></div>
        <?php endif; ?>
    </div>

</body>
</html>