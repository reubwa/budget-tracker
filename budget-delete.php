<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
$roomtID = isset($_GET['budgetID']) ? $_GET['budgetID'] : '';

$db = new SQLite3('budgetTracker.db');

$stmt = $db -> prepare("DELETE FROM Budget WHERE budgetID = :tid");
$stmt -> bindValue(':tid',$roomtID,SQLITE3_INTEGER);
if($stmt->execute()){
    echo '<span class="material-symbols-rounded">receipt_long_off</span>';
    echo '<p>Budget deleted</p>';
}else{
    echo '<span class="material-symbols-rounded">error</span>';
    echo '<p>Failed to delete budget</p>';
}
$db -> close();
?>