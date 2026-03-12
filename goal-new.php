<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $targetDate = $_POST['targetDate'];
    $db = new SQLite3('budgetTracker.db');
    $db -> exec("CREATE TABLE IF NOT EXISTS Goal (goalID int NOT NULL UNIQUE, name TEXT NOT NULL, amount REAL NOT NULL, targetDate NUMERIC NOT NULL, PRIMARY KEY(goalID AUTOINCREMENT));");
    $stmt = $db -> prepare("INSERT INTO Goal (name, amount, targetDate) VALUES (:name, :amount, :targetDate)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':amount', $amount);
    $stmt->bindValue(':targetDate', $targetDate);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">flag_check</span>';
        echo "<p>Goal created!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to create Goal!</p>";
    }
}