<script>
    setTimeout(function() {
        window.location.href = 'handoff.html';
    }, 1000);
</script>
<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountID = $_POST['accountID'];
    $name = $_POST['name'];
    $interestRate = $_POST['interestRate'];
    $balance = $_POST['balance'];
    $type = $_POST['type'];
    $db = new SQLite3('budgetTracker.db');
    $stmt = $db -> prepare("UPDATE Account SET name=:n, interestRate=:r, balance=:b, type=:t WHERE accountID=:a");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':r', $interestRate);
    $stmt->bindValue(':b', $balance);
    $stmt->bindValue(':t', $type);
    $stmt->bindValue(':a', $accountID);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">account_balance_wallet</span>';
        echo "<p>Account updated!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to update account!</p>";
    }
}
?>