<script>
    setTimeout(function() {
        window.location.href = 'handoff.html';
    }, 1000);
</script>
<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $interestRate = $_POST['category'];
    $balance = $_POST['date'];
    $type = $_POST['cost'];
    $accountID = $_POST['accountID'];
    $budgetID = $_POST['budgetID'];
    $paymentID = $_POST['paymentID'];
    $db = new SQLite3('budgetTracker.db');
    $stmt = $db -> prepare("UPDATE Payment SET name = :n, category = :c, date = :d, cost = :co, accountID = :a, budgetID = :b WHERE paymentID = :p");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':c', $interestRate);
    $stmt->bindValue(':d', $balance);
    $stmt->bindValue(':co', $type);
    $stmt->bindValue(':a', $accountID);
    $stmt->bindValue(':b', $budgetID);
    $stmt->bindValue(':p', $paymentID);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">checkbook</span>';
        echo "<p>Payment updated!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to update payment!</p>";
    }
}
?>