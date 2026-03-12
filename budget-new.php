<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $interestRate = $_POST['startingAmount'];
    $balance = $_POST['goalID'];
    $db = new SQLite3('budgetTracker.db');
    $db -> exec("CREATE TABLE IF NOT EXISTS Budget (budgetID int NOT NULL UNIQUE, name TEXT NOT NULL, startingAmount REAL NOT NULL, goalID int, PRIMARY KEY(budgetID AUTOINCREMENT), FOREIGN KEY(goalID) REFERENCES Goal(goalID) ON UPDATE CASCADE ON DELETE CASCADE);");
    $stmt = $db -> prepare("INSERT INTO Budget (name, startingAmount, goalID) VALUES (:n, :sa, :g)");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':sa', $interestRate);
    $stmt->bindValue(':g', $balance);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">receipt_long</span>';
        echo "<p>Budget created!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to create budget!</p>";
    }
}
?>