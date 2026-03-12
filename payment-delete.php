<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
$roomtID = isset($_GET['paymentID']) ? $_GET['paymentID'] : '';

$db = new SQLite3('budgetTracker.db');

$stmt = $db -> prepare("DELETE FROM Payment WHERE paymentID = :tid");
$stmt -> bindValue(':tid',$roomtID,SQLITE3_INTEGER);
if($stmt->execute()){
    echo '<span class="material-symbols-rounded">money_off</span>';
    echo '<p>Payment deleted</p>';
}else{
    echo '<span class="material-symbols-rounded">error</span>';
    echo '<p>Failed to delete payment</p>';
}
$db -> close();
?>
