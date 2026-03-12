<link rel="stylesheet" type="text/css" href="style.css" />
<?php
// Create or open an SQLite3 database
$db = new SQLite3('budgetTracker.db');
// Check if the database was created or opened successfully
if ($db) {
    echo '<script>console.log("Database created/opened successfully!")</script>';
} else {
    echo '<script>console.log("Failed to open/create the database.")</script>';
}
$select_query = "SELECT * FROM Account WHERE accountID = ".$_GET['accountID'];
$result = $db->query($select_query);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo '<script>let balance = ' .$row['balance'].'</script>';
    echo '<script>let interestRate = ' .$row['interestRate'].'</script>';
}
?>
<script>
    function change() {
        // Convert externally defined values (from PHP) to numbers.
        const currentBalance = parseFloat(balance) || 0;
        const rate = parseFloat(interestRate) || 0;

        // Get the deposit amount and timeframe (in months)
        const deposit = parseFloat(document.getElementById('val').value) || 0;
        const months = parseFloat(document.getElementById('timeframe').value) || 0;

        // Calculate interest on the current balance.
        const interestFromBalance = (rate / 100) * currentBalance;

        // Calculate the total amount deposited over the selected timeframe.
        const totalDeposits = deposit * months;

        // Calculate interest on the deposits.
        const interestFromDeposits = (rate / 100) * totalDeposits;

        // Total with interest is the sum of the two interests.
        const totalResult = interestFromBalance + interestFromDeposits + currentBalance;

        // Display the result to 2 decimal places.
        document.getElementById('output').innerHTML = '£' + totalResult.toFixed(2);
    }
</script>
<div class="flexstack">
    <div class="flexrow">
        <h3>If I add £</h3>
        <input id="val" onchange="change()" type="number">
        <h3>a month</h3>
    </div>
    <div class="flexrow">
        <h3>I'll have</h3>
        <h3 id="output"></h3>
        <select id="timeframe" onchange="change()">
            <option value="6">in 6 months</option>
            <option value="12">in 12 months</option>
            <option value="18">in 18 months</option>
            <option value="24">in 2 years</option>
            <option value="36">in 3 years</option>
            <option value="48">in 4 years</option>
            <option value="60">in 5 years</option>
            <option value="120">in 10 years</option>
            <option value="180">in 15 years</option>
            <option value="240">in 20 years</option>
            <option value="300">in 25 years</option>
            <option value="360">in 30 years</option>
            <option value="420">in 35 years</option>
            <option value="480">in 40 years</option>
            <option value="540">in 45 years</option>
            <option value="600">in 50 years</option>
        </select>
    </div>
</div>