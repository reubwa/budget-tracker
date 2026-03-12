<!-- Output CSS and PHP-generated JavaScript values -->
<link rel="stylesheet" type="text/css" href="style.css" />
<?php
$total = 0;
// Create or open an SQLite3 database
$db = new SQLite3('budgetTracker.db');
if ($db) {
    echo '<script>console.log("Database created/opened successfully!");</script>';
} else {
    echo '<script>console.log("Failed to open/create the database.");</script>';
}

$select_query = "SELECT * FROM Goal WHERE goalID = " . intval($_GET['goalID']);
$result = $db->query($select_query);
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Outputs: a numeric amount and a date string wrapped in quotes
    echo '<script>let amount = ' . floatval($row['amount']) . ';</script>';
    echo '<script>let targetDate = "' . $row['targetDate'] . '";</script>';
} else {
    echo '<script>console.error("No goal record found.");</script>';
}

$budgetID = 0;
$budgetQuery = "SELECT * FROM Budget WHERE goalID = " . intval($_GET['goalID']);
$result = $db->query($budgetQuery);
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $budgetID = intval($row['budgetID']);
}

$paymentQuery = "SELECT * FROM Payment WHERE budgetID = " . $budgetID;
$result = $db->query($paymentQuery);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $total += floatval($row['amount']);
}
echo '<script>let total = ' . $total . ';</script>';
$db->close();
?>
<script>
    // The change() function uses the PHP-passed globals: amount, targetDate, and total.
    function change() {
        // Convert the string from PHP to a Date object
        const goalDate = new Date(targetDate);
        const today = new Date();

        // Get user input from the 'val' input field.
        const userMonthlyIncome = parseFloat(document.getElementById('val').value) || 0;

        // Calculate the difference in months between the goal date and today.
        const diff = Math.max(
            (goalDate.getFullYear() - today.getFullYear()) * 12 + (goalDate.getMonth() - today.getMonth()),
            0
        );

        // Calculate if the monthly income is sufficient
        let remaining = amount - (userMonthlyIncome * diff);
        let outputMsg = remaining > 0 ? "won't" : "will";

        // If the budget checkbox is checked, adjust the calculation
        if (document.getElementById('budgetEnb').checked == true) {
            let budgetDiff = userMonthlyIncome - total;
            let adjustedRemaining = amount - (budgetDiff * diff);
            outputMsg = adjustedRemaining > 0 ? "won't" : "will";
        }

        // For debugging, you might want to log these values:
        console.log({goalDate, today, diff, remaining, outputMsg});

        document.getElementById('output').innerHTML = outputMsg;
    }
</script>

<div class="flexstack">
    <div class="flexrow">
        <h3>If I earn £</h3>
        <input id="val" onchange="change()" type="number">
        <h3>a month</h3>
    </div>
    <div class="flexrow">
        <h3>I</h3>
        <h3 id="output"></h3>
        <h3>reach my goal on time</h3>
    </div>
    <div class="flexrow">
        <input type="checkbox" id="budgetEnb" onchange="change()">
        <label for="budgetEnb">Factor in associated budget?</label>
    </div>
</div>
