<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body style="background-color:white">
    <?php
        $budgetID = $_GET['budgetID'] ?? "";
        $db = new SQLite3("budgetTracker.db");
        $stmt = $db->prepare("SELECT * FROM Budget WHERE budgetID = :accountID");
        $stmt->bindValue(":accountID", $budgetID, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $budgetID = $row['budgetID'];
        $accountName = $row['name'];
        $startingAmount = $row['startingAmount'];
        $goalID = $row['goalID'];
    ?>
    <div class="fieldset">
        <form action="budget-update.php" method="post" autocomplete="on">
            <input type="number" readonly placeholder=<?php echo $budgetID?> value=<?php echo $budgetID?> id="budgetID" name="budgetID" step="1" required>
            <label for="name">Name</label>
            <input id="name" name="name" required placeholder=<?php echo $accountName?> value=<?php echo $accountName?>>
            <label for="startingAmount">Starting Amount (£)</label>
            <input type="number" id="startingAmount" name="startingAmount" step="0.01" required placeholder=<?php echo $startingAmount?> value=<?php echo $startingAmount?>>
            <label for="goal">Goal</label>
            <select name="goalID" id="goal">
                <?php
                $db = new SQLite3('budgetTracker.db');
                // Check if the database was created or opened successfully
                if ($db) {
                    echo '<script>console.log("Database created/opened successfully!")</script>';
                } else {
                    echo '<script>console.log("Failed to open/create the database.")</script>';
                }
                $select_query = "SELECT * FROM Goal";
                $result = $db->query($select_query);
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    if($row['goalID'] == $goalID) {
                        echo '<option value="' . $row['goalID'] . '" selected>' . $row['name'] . '</option>';
                    }else{
                        echo '<option value="' . $row['goalID'] . '">' . $row['name'] . '</option>';
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