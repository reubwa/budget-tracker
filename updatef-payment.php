<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body style="background-color: white;">
    <?php
        $paymentID = $_GET['paymentID'] ?? "";
        $db = new SQLite3("budgetTracker.db");
        $stmt = $db->prepare("SELECT * FROM Payment WHERE paymentID = :accountID");
        $stmt->bindValue(":accountID", $paymentID, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $paymentID = $row['paymentID'];
        $name = $row['name'];
        $category = $row['category'];
        $date = $row['date'];
        $cost = $row['cost'];
        $accountID = $row['accountID'];
        $budgetID = $row['budgetID'];
    ?>
    <div class="fieldset">
        <form action="payment-update.php" method="post" autocomplete="on">
            <input type="number" readonly placeholder=<?php echo $paymentID?> value=<?php echo $paymentID?> id="paymentID" name="paymentID" step="1" required/>
            <label for="name">Name</label>
            <input id="name" name="name" required placeholder=<?php echo $name?> value=<?php echo $name?>>
            <label for="category">Category</label>
            <input id="category" name="category" required placeholder=<?php echo $category?> value=<?php echo $category?>>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required placeholder=<?php echo $date?> value=<?php echo $date?>>
            <label for="startingAmount">Cost (£)</label>
            <input type="number" id="cost" name="cost" step="0.01" required placeholder=<?php echo $cost?> value=<?php echo $cost?>>
            <label for="account">Account</label>
            <select name="accountID" id="account" required>
                <?php
                $db = new SQLite3('budgetTracker.db');
                // Check if the database was created or opened successfully
                if ($db) {
                    echo '<script>console.log("Database created/opened successfully!")</script>';
                } else {
                    echo '<script>console.log("Failed to open/create the database.")</script>';
                }
                $select_query = "SELECT * FROM Account";
                $result = $db->query($select_query);
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    if($row['accountID'] == $accountID){
                        echo '<option value="' . $row['accountID'] . '" selected>' . $row['name'] . '</option>';
                    }else{
                        echo '<option value="' . $row['accountID'] . '">' . $row['name'] . '</option>';
                    }
                }
                ?>
            </select>
            <label for="budget">Budget</label>
            <select name="budgetID" id="budget" required>
                <?php
                $db = new SQLite3('budgetTracker.db');
                // Check if the database was created or opened successfully
                if ($db) {
                    echo '<script>console.log("Database created/opened successfully!")</script>';
                } else {
                    echo '<script>console.log("Failed to open/create the database.")</script>';
                }
                $select_query = "SELECT * FROM Budget";
                $result = $db->query($select_query);
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    if($row['budgetID'] == $budgetID){
                        echo '<option value="' . $row['budgetID'] . '" selected>' . $row['name'] . '</option>';
                    }else{
                        echo '<option value="' . $row['budgetID'] . '">' . $row['name'] . '</option>';
                    }
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