<head><link rel="icon" type="image/x-icon" href="favicon.ico"></head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const cats = [];
    const totals = [];
    function toggleChartCol(){
        if(document.getElementById('col2').style.display == 'none'){
            document.getElementById('col2').style.display = 'flex'
        }else{
            document.getElementById('col2').style.display = 'none'
        }
    }
    function toggleCatChart(){
        if(document.getElementById('myChart').style.display == 'none'){
            document.getElementById('myChart').style.display = 'flex'
        }else{
            document.getElementById('myChart').style.display = 'none'
        }
    }
    function toggleTable(){
        if(document.getElementById('tb').style.display == 'none' && document.getElementById('pagination').style.display == 'none'){
            document.getElementById('tb').style.removeProperty('display');
            document.getElementById('pagination').style.removeProperty('display');
            document.getElementById('col2').style.width = '17%';
        }else{
            document.getElementById('tb').style.display = 'none';
            document.getElementById('pagination').style.display = 'none';
            document.getElementById('col2').style.width = '100%';
        }
    }
    function toggleCol1(){
        if(document.getElementById('ca').style.display == 'none'){
            document.getElementById('ca').style.display = 'flex'
            document.getElementById('col2').style.width = '17%';
        }else{
            document.getElementById('ca').style.display = 'none'
            document.getElementById('col2').style.width = '100%';
        }
    }
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="script.js"></script>
<title>Budget Tracker</title>
<body onload="loadNavbar()">
<div class="root-split" style="display: flex; flex-direction: row;width: 100%;">
    <div id="sidebar-container"></div>
    <div class="root-fb">
    <div class="toolbar">
        <h1 class="list-header">Accounts</h1>
        <div class="tb-btn-group">
            <span class="material-symbols-rounded" id="tb-btn" onclick="toggleCol1()">space_dashboard</span>
            <span class="material-symbols-rounded" id="tb-btn" onclick="toggleChartCol()">show_chart</span>
            <span class="material-symbols-rounded" id="tb-btn" onclick="document.getElementById('newAccount').showModal()">add</span>
        </div>
    </div>
    <div class="flexbox">
        <div class="col1" id="ca">
            <div class="content-area">
                <div class="pane-header">
                    <h3>Table</h3>
                    <span class="material-symbols-rounded" onclick="toggleTable()" id="fold">unfold_less</span>
                </div>
                <?php
                // Create or open an SQLite3 database
                $db = new SQLite3('budgetTracker.db');
                // Check if the database was created or opened successfully
                if ($db) {
                    echo '<script>console.log("Database created/opened successfully!")</script>';
                } else {
                    echo '<script>console.log("Failed to open/create the database.")</script>';
                }
                $records_per_page = 4;
                $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                if ($current_page < 1) $current_page = 1;
                $offset = ($current_page - 1) * $records_per_page;

                $total_query = "SELECT COUNT(*) as total FROM Account";
                $total_result = $db->query($total_query);
                $total_row = $total_result->fetchArray(SQLITE3_ASSOC);
                $total_records = $total_row['total'];
                $total_pages = ceil($total_records / $records_per_page);

                $select_query = "SELECT * FROM Account LIMIT $offset, $records_per_page";
                $result = $db->query($select_query);
                echo '<table id="tb">';
                echo '<thead>';
                echo '<tr>';
                echo '<td>ID</td>';
                echo '<td>Name</td>';
                echo '<td>Interest Rate (%)</td>';
                echo '<td>Balance (£)</td>';
                echo '<td>Type</td>';
                echo '<td colspan="3"></td>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $bookingID = $row['accountID'];
                    echo '<tr><td>' . $row['accountID'] . '</td>' . '<td>' . $row['name'] . '</td>' . '<td>' . $row['interestRate'] . '</td>' . '<td>' . $row['balance'] . '</td>' . '<td>' . $row['type'] . '</td> <td><a style="cursor: pointer;" onclick="raiseUpdate('.$bookingID.')"><span class="material-symbols-rounded">edit</span></a></td> <td><a style="cursor: pointer;" onclick="deleteDialog('.$bookingID.')"><span class="material-symbols-rounded">delete</span></a></td>'.'<td><a href="inspect-account.php?accountID='.$bookingID.'"><span class="material-symbols-rounded">payments</span></a></td>'.' </tr>';
                    echo '<script type="text/JavaScript">cats.push("'.$row['name'].'")</script>';
                    echo '<script type="text/JavaScript">totals.push("'.$row['balance'].'")</script>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '<div id="pagination">';
                //pagination
                if($current_page > 1){
                    $prev_page = $current_page - 1;
                    echo "<a href='?page=$prev_page'><button class='paginator-direction' >←</button></a>";
                }
                for ($i = 1; $i <= $total_pages; $i++){
                    if($i==$current_page){
                        echo "<button class='paginator-page-selected' disabled>$i</button>";
                    } else {
                        echo "<a href='?page=$i'><button class='paginator-page'>$i</button></a>";
                    }
                }
                if($current_page<$total_pages){
                    $next_page = $current_page + 1;
                    echo "<a href='?page=$next_page'><button class='paginator-direction'>→</button></a>";
                }
                echo '</div>';
                $db->close();
                ?>
            </div>
        </div>
        <div class="col2" id="col2">
            <div class="subpane">
                <div class="pane-header">
                    <h3>Accounts by Balance</h3>
                    <span class="material-symbols-rounded" onclick="toggleCatChart()" id="fold">unfold_less</span>
                </div>
                <canvas id="myChart" style="width:100%;max-width:700px;max-height: 500px;"></canvas>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    new Chart("myChart", {
        type: "pie",
        data: {
            labels: cats,
            datasets: [{
                data: totals
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
    function nextPage(){
        document.getElementById('newAccount-pg1').style.display = 'none';
        document.getElementById('newAccount-pg2').style.display = 'flex';
    }
    function completeDialog(){
        document.getElementById('newAccount').close();
        location.reload();
    }
    function completeDeleteDialog(){
        document.getElementById('delAccount').close();
        document.getElementById('delAccount').style.removeProperty('display');
        location.reload();
    }
    function deleteDialog(delpayid){
        document.getElementById('delAccount').showModal();
        document.getElementById('delAccount').style.display = 'flex';
        document.getElementById('delOutputArea').src = "account-delete.php?accountID="+delpayid;
    }
    function raiseUpdate(updpayid){
        document.getElementById('updAccount').showModal();
        document.getElementById('updAccount').style.display = 'flex';
        document.getElementById('updOutputArea').src = "updatef-account.php?accountID="+updpayid;
    }
    function handoffHandler(){
        requestAnimationFrame(handoffHandler);
        if(document.getElementById('updOutputArea').contentWindow.location.href.endsWith('handoff.html')){
            document.getElementById('upd-done-btn').style.removeProperty('display');
        }
    }
    function finishUpdate(){
        document.getElementById('upd-close').style.display = 'none';
        document.getElementById('updAccount').close();
        document.getElementById('updAccount').style.display = 'none';
        location.reload();
        cancelAnimationFrame(handoffHandler);
    }
</script>
<dialog id="newAccount">
    <div id="newAccount-pg1">
        <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('newAccount').close()">close</span>
        <div class="fieldset">
            <form action="account-new.php" method="post" autocomplete="on" target="outputArea" onsubmit="nextPage()">
                <label for="name">Name</label>
                <input id="name" name="name" required/>
                <label for="interestRate">Interest Rate (%)</label>
                <input type="number" id="interestRate" name="interestRate" step="0.01" required>
                <label for="balance">Balance (£)</label>
                <input type="number" id="balance" name="balance" step="0.01" required>
                <label for="type">Type</label>
                <select name="type" id="type" required>
                    <option value="Current">Current Account</option>
                    <option value="Savings">Savings Account</option>
                </select>
                <br>
                <input type="submit">
                <input type="reset">
            </form>
        </div>
    </div>
    <div id="newAccount-pg2" style="display: none;flex-direction: column;">
        <iframe name="outputArea"></iframe>
        <button onclick="completeDialog()" style="margin-top:5px;">Done</button>
    </div>
</dialog>
<dialog id="delAccount" style="display: none;flex-direction: column;">
    <iframe id="delOutputArea"></iframe>
    <button onclick="completeDeleteDialog()" style="margin-top:5px;">Done</button>
</dialog>
<dialog id="updAccount" style="display: none;flex-direction: column">
    <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('updAccount').close();document.getElementById('updAccount').style.display = 'none';" id="upd-close">close</span>
    <iframe id="updOutputArea" onload="handoffHandler()" style="height: 580px;width:360px;"></iframe>
    <button onclick="finishUpdate()" style="margin-top:5px;display: none;" id="upd-done-btn">Done</button>
</dialog>
</body>
