<script>
    setTimeout(function() {
        window.location.href = 'handoff.html';
    }, 1000);
</script>
<?php
echo '<link rel="stylesheet" type="text/css" href="style.css" />';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $targetDate = $_POST['targetDate'];
    $goalID = $_POST['goalID'];
    $db = new SQLite3('budgettracker.db');
    $stmt = $db -> prepare("UPDATE Goal SET name = :name, amount = :amount, targetDate = :targetDate WHERE goalID = :goalID");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':amount', $amount);
    $stmt->bindValue(':targetDate', $targetDate);
    $stmt->bindValue(':goalID', $goalID);
    if($stmt->execute()) {
        echo '<span class="material-symbols-rounded">flag_check</span>';
        echo "<p>Goal updated!</p>";
    }else{
        echo '<span class="material-symbols-rounded">error</span>';
        echo "<p>Failed to update Goal!</p>";
    }
}
?>