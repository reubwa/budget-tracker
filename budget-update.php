<script>
    setTimeout(function() {
        window.location.href = 'handoff.html';
    }, 1000);
</script>
<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $interestRate = $_POST['startingAmount'];
    $balance = $_POST['goalID'];
    $budgetID = $_POST['budgetID'];
    $db = new SQLite3('budgetTracker.db');
    $stmt = $db -> prepare("UPDATE Budget SET name =:n, startingAmount =:sa, goalID =:g WHERE budgetID =:a");
    $stmt->bindValue(':n', $name);
    $stmt->bindValue(':sa', $interestRate);
    $stmt->bindValue(':g', $balance);
    $stmt->bindValue(':a', $budgetID);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">order_approve</span>';
        echo "<p>Budget updated!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to update budget!</p>";
    }
}
?>