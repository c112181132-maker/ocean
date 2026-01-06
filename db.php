<?php
// 確保最上方沒有任何空格或空行
$host = 'localhost';
$dbname = 'ocean_db';
$user = 'root';
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 這裡可以加一行測試，成功連線就不會顯示任何東西
} catch(PDOException $e) {
    die("資料庫連線失敗: " . $e->getMessage());
}
// 結尾不需要加上 ?> 