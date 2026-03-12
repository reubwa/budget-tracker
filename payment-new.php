<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $interestRate = $_POST['category'];
    $balance = $_POST['date'];
    $type = $_POST['cost'];
    $accountID = $_POST['accountID'];
    $budgetID = $_POST['budgetID'];
    $db = new SQLite3('budgetTracker.db');
    $db -> exec("CREATE TABLE IF NOT EXISTS Payment (paymentID int NOT NULL UNIQUE, name TEXT NOT NULL, category TEXT NOT NULL, date NUMERIC NOT NULL, cost REAL NOT NULL, accountID int NOT NULL, budgetID int, PRIMARY KEY(paymentID AUTOINCREMENT), FOREIGN KEY(accountID) REFERENCES Account(accountID) ON UPDATE CASCADE ON DELETE CASCADE, FOREIGN KEY(budgetID) REFERENCES Budget(budgetID) ON UPDATE CASCADE ON DELETE CASCADE);");
    $stmt = $db -> prepare("INSERT INTO Payment (name, category, date, cost,accountID,budgetID) VALUES (:n, :c, :d, :co, :a, :b)");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':c', $interestRate);
    $stmt->bindValue(':d', $balance);
    $stmt->bindValue(':co', $type);
    $stmt->bindValue(':a', $accountID);
    $stmt->bindValue(':b', $budgetID);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">price_check</span>';
        echo "<p>Payment created!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to create payment!</p>";
    }
}
?>