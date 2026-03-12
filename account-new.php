<?php
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
echo '<script src="script.js"></script>';
echo '<title>Budget Tracker</title>';
echo '<body onload="loadNavbar()">';
echo '<div id="navbar-container"></div>';
echo '<div class="content-area">';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $interestRate = $_POST['interestRate'];
    $balance = $_POST['balance'];
    $type = $_POST['type'];
    $db = new SQLite3('budgetTracker.db');
    $db -> exec("CREATE TABLE IF NOT EXISTS Account (accountID int NOT NULL UNIQUE, name TEXT NOT NULL, interestRate REAL NOT NULL, balance REAL NOT NULL, type TEXT NOT NULL, PRIMARY KEY(accountID AUTOINCREMENT));");
    $stmt = $db -> prepare("INSERT INTO Account (name, interestRate, balance, type) VALUES (:n, :r, :b, :t)");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':r', $interestRate);
    $stmt->bindValue(':b', $balance);
    $stmt->bindValue(':t', $type);
    if($stmt->execute()) {
        echo "<p>Account created!</p>";
    }else{
        echo "<p>Failed to create account!</p>";
    }
}
?>