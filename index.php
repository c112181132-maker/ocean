<?php
session_start();


if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'db.php';
// 新增資料
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO exploration_logs (location, depth, temperature, discovery) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['location'], $_POST['depth'], $_POST['temp'], $_POST['discovery']]);
}

// 刪除資料
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM exploration_logs WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

// 讀取所有資料
$logs = $pdo->query("SELECT * FROM exploration_logs ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>海底探測管理系統</title>
    <style>
    body { 
        background-color: #001219; 
        color: #e9d8a6; 
        font-family: "Microsoft JhengHei", sans-serif; 
        padding: 20px;
    }
    h1 { color: #94d2bd; border-left: 5px solid #0a9396; padding-left: 15px; }
    .form-box { 
        background: #005f73; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        margin-bottom: 30px;
    }
    input, button, textarea { 
        padding: 8px; margin: 5px; border-radius: 4px; border: none; 
    }
    button { background: #ee9b00; color: white; cursor: pointer; font-weight: bold; }
    button:hover { background: #ca6702; }
    table { width: 100%; border-collapse: collapse; background: #0a1217; }
    th { background: #0a9396; color: white; padding: 12px; }
    td { border-bottom: 1px solid #333; padding: 10px; color: #fff; }
    tr:hover { background: #142129; }
    a { color: #94d2bd; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .logout-btn { float: right; color: #ae2012; }
</style>
</head>
<body>
    <h1>Deep Sea Explorer | 海底探測日誌</h1>
    <a href="logout.php">登出系統</a>

    <div class="form-box">
        <h3>新增探測紀錄</h3>
        <form method="POST">
            <input type="text" name="location" placeholder="探測位置" required>
            <input type="number" step="0.1" name="depth" placeholder="深度 (m)" required>
            <input type="number" step="0.1" name="temp" placeholder="溫度 (°C)" required>
            <input type="text" name="discovery" placeholder="發現物描述">
            <button type="submit" name="add">提交紀錄</button>
        </form>
    </div>

    <table>
        <tr>
            <th>時間</th><th>地點</th><th>深度</th><th>溫度</th><th>發現描述</th><th>操作</th>
        </tr>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= $log['created_at'] ?></td>
            <td><?= htmlspecialchars($log['location']) ?></td>
            <td><?= $log['depth'] ?> m</td>
            <td><?= $log['temperature'] ?> °C</td>
            <td><?= htmlspecialchars($log['discovery']) ?></td>
            <td>
                <a href="edit.php?id=<?= $log['id'] ?>">編輯</a> | 
                <a href="index.php?delete=<?= $log['id'] ?>" onclick="return confirm('確定要刪除嗎？')">刪除</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>