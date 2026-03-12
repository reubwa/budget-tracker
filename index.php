
<head><link rel="icon" type="image/x-icon" href="favicon.ico"></head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const acctCats = [];
    const acctTotals = [];
    const budgCats = [];
    const budgTotals = [];
    const goalCats = [];
    const goalTotals = [];
    const payCats = [];
    const payTotals = [];
    const dates = [];
    const dateTots = [];
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="script.js"></script>
<title>Budget Tracker</title>
<body onload="loadNavbar()">
    <div class="root-split" style="display: flex; flex-direction: row;width: 100%;">
        <div id="sidebar-container"></div>
        <div class="root-fx-col" style="display: flex;flex-direction: column;gap:10px;">
            <div class="fx-row" style="display: flex;flex-direction: row;gap:10px;">
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Accounts by Balance</h3>
                    </div>
                    <canvas id="acctChart" style="width:100%;max-width:700px;max-height: 500px;"></canvas>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Budget by Starting Amount</h3>
                    </div>
                    <canvas id="dateChart" style="width:100%;max-width:700px;max-height: 500px;height:300px;"></canvas>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Goals by Total</h3>
                    </div>
                    <canvas id="goalChart" style="width:100%;max-width:700px;max-height: 500px;"></canvas>
                </div>
            </div>
            <div class="fx-row" style="display: flex;flex-direction: row;gap:10px;">
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Spending by Date</h3>
                    </div>
                    <canvas id="payDateChart" style="width:100%;max-width:700px;max-height: 700px;height: 300px;"></canvas>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Spending by Name</h3>
                    </div>
                    <canvas id="payNameChart" style="width:100%;max-width:700px;max-height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
// Create or open an SQLite3 database
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
    echo '<script type="text/JavaScript">acctCats.push("'.$row['name'].'")</script>';
    echo '<script type="text/JavaScript">acctTotals.push("'.$row['balance'].'")</script>';
}
$select_query = "SELECT * FROM Budget";
$result = $db->query($select_query);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo '<script type="text/JavaScript">budgCats.push("'.$row['name'].'")</script>';
    echo '<script type="text/JavaScript">budgTotals.push("'.$row['startingAmount'].'")</script>';
}
$select_query = "SELECT * FROM Goal";
$result = $db->query($select_query);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo '<script type="text/JavaScript">goalCats.push("'.$row['name'].'")</script>';
    echo '<script type="text/JavaScript">goalTotals.push("'.$row['amount'].'")</script>';
}
$select_query = "SELECT * FROM Payment";
$result = $db->query($select_query);
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo '<script type="text/JavaScript">payCats.push("'.$row['name'].'")</script>';
    echo '<script type="text/JavaScript">payTotals.push("'.$row['cost'].'")</script>';
    echo '<script type="text/JavaScript">dates.push("'.$row['date'].'")</script>';
    echo '<script type="text/JavaScript">dateTots.push("'.$row['cost'].'")</script>';
}
$db -> close();
?>
<script>
    const filtCats = payCats.filter((item, index) => payCats.indexOf(item) === index);
    const catTots = [];
    payCats.forEach(el => {
        filtCats.forEach(fEl => {
            if(fEl == el){
                if(typeof catTots[filtCats.indexOf(fEl)] == "undefined"){
                    catTots.push(payTotals[payCats.indexOf(el)]);
                }else{
                    catTots[filtCats.indexOf(fEl)] = parseInt(catTots[filtCats.indexOf(fEl)]) + parseInt(payTotals[payCats.indexOf(el)]);
                }
            }
        })
    })
    const filtDates = dates.filter((item, index) => dates.indexOf(item) === index);
    const filtDateTots = [];
    dates.forEach(el => {
        filtDates.forEach(fEl => {
            if(fEl == el){
                if(typeof filtDateTots[filtDates.indexOf(fEl)] == "undefined"){
                    filtDateTots.push(dateTots[dates.indexOf(el)]);
                }else{
                    filtDateTots[filtDates.indexOf(fEl)] = parseInt(filtDateTots[filtDates.indexOf(fEl)]) + parseInt(dateTots[dates.indexOf(el)]);
                }
            }
        })
    })
    new Chart("payNameChart", {
        type: "pie",
        data: {
            labels: filtCats,
            datasets: [{
                data: catTots
            }]
        },
        options: {
            plugins:{
                title: {
                    display: false,
                    text: "Spending by Category"
                }
            }
        }
    });
    new Chart("payDateChart", {
        type: "bar",
        data: {
            labels: filtDates,
            datasets: [{
                data: filtDateTots
            }]
        },
        options: {
            plugins:{
                title: {
                    display: false,
                    text: "Spending by Date"
                }
            }
        }
    });
    new Chart("acctChart", {
        type: "pie",
        data: {
            labels: acctCats,
            datasets: [{
                data: acctTotals
            }]
        },
        options: {
            plugins:{
                title: {
                    display: false,
                    text: "Accounts by Balance"
                }
            }
        }
    });
    new Chart("dateChart", {
        type: "bar",
        data: {
            labels: budgCats,
            datasets: [{
                data: budgTotals
            }]
        },
        options: {
            plugins:{
                title: {
                    display: false,
                    text: "Budget by Starting Amount"
                }
            }
        }
    });
    new Chart("goalChart", {
        type: "pie",
        data: {
            labels: goalCats,
            datasets: [{
                data: goalTotals
            }]
        },
        options: {
            plugins:{
                title: {
                    display: false,
                    text: "Accounts by Balance"
                }
            }
        }
    });
</script>
