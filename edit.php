<?php
// 1. 開啟錯誤報告（這行能讓空白頁顯示出報錯訊息）
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. 檢查登入狀態
if (!isset($_SESSION['user_id'])) { 
    die("未登入，請先回 login.php"); 
}

// 3. 引入資料庫並檢查變數
require_once 'db.php';
if (!isset($pdo)) {
    die("資料庫連線變數 \$pdo 遺失，請檢查 db.php 是否正確。");
}

// 4. 取得 ID 並抓取資料
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) { 
    die("未提供 ID，無法編輯資料。"); 
}

$stmt = $pdo->prepare("SELECT * FROM exploration_logs WHERE id = ?");
$stmt->execute([$id]);
$log = $stmt->fetch();

if (!$log) {
    die("找不到 ID 為 $id 的紀錄。");
}

// 5. 處理更新動作
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE exploration_logs SET location=?, depth=?, temperature=?, discovery=? WHERE id=?");
    $stmt->execute([$_POST['location'], $_POST['depth'], $_POST['temp'], $_POST['discovery'], $id]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>編輯探測數據</title>
    <style>
        body { background-color: #001219; color: #e9d8a6; font-family: sans-serif; padding: 20px; }
        .edit-container { max-width: 500px; margin: auto; background: #005f73; padding: 30px; border-radius: 10px; }
        input, textarea { width: 90%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
        button { background: #ee9b00; color: white; padding: 10px 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>修正探測紀錄 #<?= htmlspecialchars($id) ?></h2>
    <form method="POST">
        位置：<br><input type="text" name="location" value="<?= htmlspecialchars($log['location']) ?>" required><br>
        深度 (m)：<br><input type="number" step="0.1" name="depth" value="<?= htmlspecialchars($log['depth']) ?>" required><br>
        溫度 (°C)：<br><input type="number" step="0.1" name="temp" value="<?= htmlspecialchars($log['temperature']) ?>" required><br>
        發現描述：<br>
        <textarea name="discovery" rows="5"><?= htmlspecialchars($log['discovery']) ?></textarea><br>
        <button type="submit" name="update">確認更新</button>
        <a href="index.php" style="color: #94d2bd; margin-left: 10px;">取消返回</a>
    </form>
</div>

</body>
</html>