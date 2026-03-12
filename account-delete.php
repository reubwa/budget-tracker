<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
$roomtID = isset($_GET['accountID']) ? $_GET['accountID'] : '';

$db = new SQLite3('budgetTracker.db');

$stmt = $db -> prepare("DELETE FROM Account WHERE accountID = :tid");
$stmt -> bindValue(':tid',$roomtID,SQLITE3_INTEGER);
if($stmt->execute()){
    echo '<span class="material-symbols-rounded">account_balance_wallet</span>';
    echo '<p>Account deleted</p>';
}else{
    echo '<span class="material-symbols-rounded">error</span>';
    echo '<p>Failed to delete account</p>';
}
$db -> close();
?>