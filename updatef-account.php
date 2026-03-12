<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body style="background-color:white">
    <?php
        $accountID = $_GET['accountID'] ?? "";
        $db = new SQLite3("budgetTracker.db");
        $stmt = $db->prepare("SELECT * FROM Account WHERE accountID = :accountID");
        $stmt->bindValue(":accountID", $accountID, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $accountID = $row['accountID'];
        $accountName = $row['name'];
        $interestRate = $row['interestRate'];
        $balance = $row['balance'];
        $type = $row['type'];
    ?>
    <div class="fieldset">
        <form action="account-update.php" method="post" autocomplete="on">
            <input type="number" readonly placeholder=<?php echo $accountID?> value=<?php echo $accountID?> id="accountID" name="accountID" step="1" required/>
            <label for="name">Name</label>
            <input id="name" name="name" required placeholder=<?php echo $accountName?> value=<?php echo $accountName?>>
            <label for="interestRate">Interest Rate (%)</label>
            <input type="number" id="interestRate" name="interestRate" step="0.01" required placeholder=<?php echo $interestRate?> value=<?php echo $interestRate?>>
            <label for="balance">Balance (£)</label>
            <input type="number" id="balance" name="balance" step="0.01" required placeholder=<?php echo $balance?> value=<?php echo $balance?>>
            <label for="type">Type</label>
            <select name="type" id="type" required>
                <?php
                if($type == "Current") {
                    echo "<option value='Current' selected>Current Account</option>";
                    echo "<option value='Savings'>Savings Account</option>";
                }else{
                    echo "<option value='Current'>Current Account</option>";
                    echo "<option value='Savings' selected>Savings Account</option>";
                }
                ?>
            </select>
            <br>
            <input type="submit">
            <input type="reset">
        </form>
    </div>
</body>
</html>