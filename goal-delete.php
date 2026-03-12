<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
$roomtID = isset($_GET['goalID']) ? $_GET['goalID'] : '';

$db = new SQLite3('budgetTracker.db');

$stmt = $db -> prepare("DELETE FROM Goal WHERE goalID = :tid");
$stmt -> bindValue(':tid',$roomtID,SQLITE3_INTEGER);
if($stmt->execute()){
    echo '<span class="material-symbols-rounded">flag</span>';
    echo '<p>Goal deleted</p>';
}else{
    echo '<span class="material-symbols-rounded">error</span>';
    echo '<p>Failed to delete goal</p>';
}
$db -> close();
?>
