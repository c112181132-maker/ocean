<?php
session_start();
$_SESSION['test'] = "Session 正常運作中";
echo "已設定 Session。 <a href='test_session_2.php'>點擊這裡測試下一頁</a>";
?>