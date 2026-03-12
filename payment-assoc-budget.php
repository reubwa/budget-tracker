<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
$roomtID = isset($_GET['paymentID']) ? $_GET['paymentID'] : '';
$budgetID = isset($_GET['budgetID']) ? $_GET['budgetID'] : '';
$db = new SQLite3('budgetTracker.db');

$stmt = $db -> prepare("UPDATE Payment SET budgetID = :bid WHERE paymentID = :tid");
$stmt -> bindValue(':tid',$roomtID,SQLITE3_INTEGER);
$stmt -> bindValue(':bid',$budgetID,SQLITE3_INTEGER);
if($stmt->execute()){
    echo '<span class="material-symbols-rounded">link</span>';
    echo '<p>Payment associated with budget!</p>';
}else{
    echo '<span class="material-symbols-rounded">error</span>';
    echo '<p>Failed to associate payment</p>';
}
$db -> close();
?>