<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<?php
    $goalID = $_GET['goalID'] ?? "";
    $db = new SQLite3("budgetTracker.db");
    $stmt = $db->prepare("SELECT * FROM Goal WHERE goalID = :goalID");
    $stmt->bindValue(":goalID", $goalID, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $goalID = $row["goalID"];
    $name = $row["name"];
    $amount = $row["amount"];
    $targetDate = $row["targetDate"];
?>
<div class="fieldset">
    <form action="goal-update.php" method="post" autocomplete="on">
        <input type="number" readonly placeholder=<?php echo $goalID?> value=<?php echo $goalID?> id="goalID" name="goalID" step="1" required>
        <label for="name">Name</label>
        <input id="name" name="name" required placeholder=<?php echo $name?> value=<?php echo $name?>>
        <label for="amount">Amount (£)</label>
        <input type="number" id="amount" name="amount" step="0.01" required placeholder=<?php echo $amount?> value=<?php echo $amount?>>
        <label for="targetDate">Target Date</label>
        <input type="date" id="targetDate" name="targetDate" required placeholder=<?php echo $targetDate?> value=<?php echo $targetDate?>>
        <input type="submit">
        <input type="reset">
    </form>
</div>
</body>
</html>