<?php
    $totalsp = 0;
    echo '<script>let acctID = '.$_GET['budgetID'].'</script>';
?>
<head><link rel="icon" type="image/x-icon" href="favicon.ico"></head>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let thisURL = new URL(window.location.href);
    const cats = [];
    const totals = [];
    const dates = [];
    const dateTots = [];
    function updCategory(selCat){
        thisURL.searchParams.set("category",selCat);
        window.location.replace(thisURL);
    }
    function clearCat(){
        thisURL.searchParams.delete("category");
        window.location.replace(thisURL);
    }
    function setDateWindow(startD,endD){
        thisURL.searchParams.set("startDate",startD);
        thisURL.searchParams.set("endDate",endD);
        window.location.replace(thisURL);
    }
    function toggleDS(){
        if(document.getElementById('ds').style.display == 'none'){
            document.getElementById('ds').style.display = 'flex'
        }else{
            document.getElementById('ds').style.display = 'none'
        }
    }
    function clearDate(){
        thisURL.searchParams.delete("startDate");
        thisURL.searchParams.delete("endDate");
        window.location.replace(thisURL);
    }
    function toggleChartCol(){
        if(document.getElementById('col2').style.display == 'none'){
            document.getElementById('col2').style.display = 'flex'
        }else{
            document.getElementById('col2').style.display = 'none'
        }
    }
    function toggleDateChart(){
        if(document.getElementById('dateChart').style.display == 'none'){
            document.getElementById('dateChart').style.display = 'flex'
        }else{
            document.getElementById('dateChart').style.display = 'none'
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
    function toggleCS(){
        if(document.getElementById('cs').style.display == 'none'){
            document.getElementById('cs').style.display = 'flex'
        }else{
            document.getElementById('cs').style.display = 'none'
        }
    }
    function togglePG(){
        if(document.getElementById('pg').style.display == 'none'){
            document.getElementById('pg').style.display = 'flex'
        }else{
            document.getElementById('pg').style.display = 'none'
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
            <div class="tb-btn-group">
                <span class="material-symbols-rounded" id="tb-btn" onclick="history.back()">arrow_back</span>
                <h1 class="list-header">Inspect this Budget</h1>
            </div>
            <div class="tb-btn-group">
                <span class="material-symbols-rounded" id="tb-btn" onclick="toggleCol1()">space_dashboard</span>
                <span class="material-symbols-rounded" id="tb-btn" onclick="toggleChartCol()">show_chart</span>
                <span class="material-symbols-rounded" id="tb-btn" onclick="startAssoc()">link</span>
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
                    $budgetID = htmlspecialchars(trim($_GET['budgetID']), ENT_QUOTES, 'UTF-8');
                    if(isset($_GET['category'])&&isset($_GET['startDate'])&&isset($_GET['endDate'])){
                        $selectedCategory = $_GET['category'];
                        $startDate = $_GET['startDate'];
                        $endDate = $_GET['endDate'];
                        $startDate = htmlspecialchars(trim($_GET['startDate']), ENT_QUOTES, 'UTF-8');
                        $endDate = htmlspecialchars(trim($_GET['endDate']), ENT_QUOTES, 'UTF-8');
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE budgetID = ". $budgetID ." AND category = "."'"."$selectedCategory"."' AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'";
                        $select_query = "SELECT * FROM Payment WHERE budgetID = ". $budgetID ." AND category = "."'"."$selectedCategory"."' AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'"." LIMIT $offset, $records_per_page";
                    }else if(isset($_GET['category'])){
                        $selectedCategory = $_GET['category'];
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE budgetID = ". $budgetID ." AND category = "."'"."$selectedCategory"."'";
                        $select_query = "SELECT * FROM Payment WHERE budgetID = ". $budgetID ." AND category = "."'"."$selectedCategory"."'"." LIMIT $offset, $records_per_page";
                    }else if(isset($_GET['startDate'])&&isset($_GET['endDate'])){
                        $startDate = $_GET['startDate'];
                        $endDate = $_GET['endDate'];
                        $startDate = htmlspecialchars(trim($_GET['startDate']), ENT_QUOTES, 'UTF-8');
                        $endDate = htmlspecialchars(trim($_GET['endDate']), ENT_QUOTES, 'UTF-8');
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE budgetID = ". $budgetID ." AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'";
                        $select_query = "SELECT * FROM Payment WHERE budgetID = ". $budgetID ." AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'"." LIMIT $offset, $records_per_page";
                    }else{
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE budgetID = ".$budgetID;
                        $select_query = "SELECT * FROM Payment WHERE budgetID = ".$budgetID." LIMIT $offset, $records_per_page";
                    }
                    $total_result = $db->query($total_query);
                    $total_row = $total_result->fetchArray(SQLITE3_ASSOC);
                    $total_records = $total_row['total'];
                    $total_pages = ceil($total_records / $records_per_page);


                    $result = $db->query($select_query);

                    echo '<table id="tb">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<td>ID</td>';
                    echo '<td>Name</td>';
                    echo '<td>Category</td>';
                    echo '<td>Date</td>';
                    echo '<td>Cost (£)</td>';
                    echo '<td>Account ID</td>';
                    echo '<td>Budget ID</td>';
                    echo '<td colspan="3"></td>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        $bookingID = $row['paymentID'];
                        echo '<tr><td>' . $row['paymentID'] . '</td>' . '<td>' . $row['name'] . '</td>' . '<td>' . $row['category'] . '</td>' . '<td>' . $row['date'] . '</td>' . '<td>' . $row['cost'] .'</td>' . '<td>' . $row['accountID'] . '</td>' . '<td>' . $row['budgetID'] .'</td> <td><a style="cursor: pointer;" onclick="deAssoc('.$bookingID.')"><span class="material-symbols-rounded">link_off</span></a></td> <td><a style="cursor: pointer;" onclick="raiseUpdate('.$bookingID.')"><span class="material-symbols-rounded">edit</span></a></td> <td><a style="cursor: pointer;" onclick="deleteDialog('.$bookingID.')"><span class="material-symbols-rounded">delete</span></a></td> </tr>';
                        if(isset($_GET['category'])){
                            echo '<script type="text/JavaScript">cats.push("'.$row['name'].'")</script>';
                            echo '<script type="text/JavaScript">totals.push("'.$row['cost'].'")</script>';
                        }else{
                            echo '<script type="text/JavaScript">cats.push("'.$row['category'].'")</script>';
                            echo '<script type="text/JavaScript">totals.push("'.$row['cost'].'")</script>';
                        }
                        echo '<script type="text/JavaScript">dates.push("'.$row['date'].'")</script>';
                        echo '<script type="text/JavaScript">dateTots.push("'.$row['cost'].'")</script>';
                        $totalsp =  $totalsp + (int) $row['cost'];
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
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Categories</h3>
                        <span class="material-symbols-rounded" onclick="toggleCS()" id="fold">unfold_less</span>
                    </div>
                    <?php
                    $db = new SQLite3('budgetTracker.db');
                    $cats = array();
                    $catQuery = "SELECT category FROM Payment";
                    $catResult = $db->query($catQuery);
                    echo '<div class="cat-sels" id="cs">';
                    while ($row = $catResult->fetchArray(SQLITE3_ASSOC)) {
                        $cat = $row['category'];
                        if(isset($_GET['category'])&&$cat == $selectedCategory&&in_array($cat,$cats)==false){
                            echo '<a onclick="updCategory(\'' . $cat . '\')" class="cat-selector-selected">' . $cat . '</a>';
                            $cats[] = $cat;
                        }else if(in_array($cat,$cats)==false){
                            echo '<a style="cursor:pointer;" onclick="updCategory(\'' . $cat . '\')" class="cat-selector">' . $cat . '</a>';
                            $cats[] = $cat;
                        }
                    }
                    if(isset($_GET['category'])){
                        echo '<a class="cat-selector" style="cursor: pointer;" onclick="clearCat()">All</a>';
                    }
                    echo '</div>';
                    $db->close();
                    ?>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Amount of Budget Spent</h3>
                        <span class="material-symbols-rounded" onclick="togglePG()" id="fold">unfold_less</span>
                    </div>
                    <div style="display:flex;flex-direction:row;align-items:center;gap: 10px;margin-top:-15px;" id="pg">
                        <?php
                        $db = new SQLite3('budgetTracker.db');
                        $totalQuery = "SELECT startingAmount FROM Budget WHERE budgetID = " . $_GET['budgetID'];
                        $totalResult = $db->query($totalQuery);
                        $total = 0;
                        while ($row = $totalResult->fetchArray(SQLITE3_ASSOC)) {
                            $total = $row['startingAmount'];
                        }
                        echo '<progress max="'.(string) $total.'" value="'.(string) $totalsp.'"></progress>';
                        echo "<p>".round(($totalsp/$total)*100).'% (£'.$totalsp.')'."</p>";

                        ?>
                    </div>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Date Window</h3>
                        <span class="material-symbols-rounded" onclick="toggleDS()" id="fold">unfold_less</span>
                    </div>
                    <div id="ds" style="display:flex;flex-direction: row;gap: 10px;align-items:center;">
                        <label for="startDa">From</label>
                        <?php
                        if(isset($_GET['startDate'])){
                            echo '<input id="startDa" name="startDa" type="date" value="'.$_GET['startDate'].'">';
                        }else{
                            echo '<input id="startDa" name="startDa" type="date">';
                        }
                        ?>
                        <label for="endDa">To</label>
                        <?php
                        if(isset($_GET['endDate'])){
                            echo '<input id="endDa" name="endDa" type="date" value="'.$_GET['endDate'].'">';
                        }else{
                            echo '<input id="endDa" name="endDa" type="date">';
                        }
                        ?>
                        <button onclick="setDateWindow(document.getElementById('startDa').value,document.getElementById('endDa').value)">Submit</button>
                        <button onclick="clearDate()">Clear</button>
                    </div>
                </div>
            </div>
            <div class="col2" id="col2">
                <div class="subpane">
                    <div class="pane-header">
                        <?php
                        if(isset($_GET['category'])){
                            echo '<h3>Spending by Name</h3>';
                        }else{
                            echo '<h3>Spending by Category</h3>';
                        }
                        ?>
                        <span class="material-symbols-rounded" onclick="toggleCatChart()" id="fold">unfold_less</span>
                    </div>
                    <canvas id="myChart" style="width:100%;max-width:700px;max-height: 500px;"></canvas>
                </div>
                <div class="subpane">
                    <div class="pane-header">
                        <h3>Spending by Date</h3>
                        <span class="material-symbols-rounded" onclick="toggleDateChart()" id="fold">unfold_less</span>
                    </div>
                    <canvas id="dateChart" style="width:100%;max-width:700px;max-height: 500px;height:200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const filtCats = cats.filter((item, index) => cats.indexOf(item) === index);
    const catTots = [];
    cats.forEach(el => {
        filtCats.forEach(fEl => {
            if(fEl == el){
                if(typeof catTots[filtCats.indexOf(fEl)] == "undefined"){
                    catTots.push(totals[cats.indexOf(el)]);
                }else{
                    catTots[filtCats.indexOf(fEl)] = parseInt(catTots[filtCats.indexOf(fEl)]) + parseInt(totals[cats.indexOf(el)]);
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
    new Chart("myChart", {
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
    new Chart("dateChart", {
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
    function completeDeleteDialog(){
        document.getElementById('delPayment').close();
        document.getElementById('delPayment').style.removeProperty('display');
        location.reload();
    }
    function deleteDialog(delpayid){
        document.getElementById('delPayment').showModal();
        document.getElementById('delPayment').style.display = 'flex';
        document.getElementById('delOutputArea').src = "payment-delete.php?paymentID="+delpayid;
    }
    function raiseUpdate(updpayid){
        document.getElementById('updPayment').showModal();
        document.getElementById('updPayment').style.display = 'flex';
        document.getElementById('updOutputArea').src = "updatef-payment.php?paymentID="+updpayid;
    }
    function handoffHandler(){
        requestAnimationFrame(handoffHandler);
        if(document.getElementById('updOutputArea').contentWindow.location.href.endsWith('handoff.html')){
            document.getElementById('upd-done-btn').style.removeProperty('display');
        }
    }
    function finishUpdate(){
        document.getElementById('upd-close').style.display = 'none';
        document.getElementById('updPayment').close();
        document.getElementById('updPayment').style.display = 'none';
        location.reload();
        cancelAnimationFrame(handoffHandler);
    }
    function deAssoc(payid){
        document.getElementById('deAssoc').showModal();
        document.getElementById('deAssoc').style.display = 'flex';
        document.getElementById('deAssocOutputArea').src = "payment-deassoc-budget.php?paymentID="+payid;
    }
    function finishDeAssoc(){
        document.getElementById('deAssoc').close();
        document.getElementById('deAssoc').style.display = 'none';
        location.reload();
    }
    function startAssoc(){
        document.getElementById('AssocDlg').showModal();
        document.getElementById('assoc-pg1').style.display = 'flex';
    }
    function assocNextPg(){
        document.getElementById('assoc-pg1').style.display = 'none';
        document.getElementById('assoc-pg2').style.display = 'flex';
        document.getElementById('assocOutputArea').src = "payment-assoc-budget.php?budgetID="+acctID+"&paymentID="+document.getElementById('paymentID').value;
    }
    function finishAssoc(){
        document.getElementById('AssocDlg').close();
        document.getElementById('assoc-pg2').style.display = 'none';
        location.reload();
    }
</script>
<dialog id="delPayment" style="display: none;flex-direction: column;">
    <iframe id="delOutputArea"></iframe>
    <button onclick="completeDeleteDialog()" style="margin-top:5px;">Done</button>
</dialog>
<dialog id="updPayment" style="display: none;flex-direction: column">
    <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('updPayment').close();document.getElementById('updPayment').style.display = 'none';" id="upd-close">close</span>
    <iframe id="updOutputArea" onload="handoffHandler()" style="height: 580px;width:360px;"></iframe>
    <button onclick="finishUpdate()" style="margin-top:5px;display: none;" id="upd-done-btn">Done</button>
</dialog>
<dialog id="deAssoc" style="display: none;flex-direction: column;">
    <iframe id="deAssocOutputArea"></iframe>
    <button onclick="finishDeAssoc()" style="margin-top:5px;">Done</button>
</dialog>
<dialog id="AssocDlg">
    <div id="assoc-pg1" style="display: none;flex-direction: column;">
        <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('AssocDlg').close();document.getElementById('AssocDlg').style.display = 'none';" id="assoc-close">close</span>
        <label for="paymentID">Payment</label>
        <select name="paymentID" id="paymentID" required>
            <?php
            $db = new SQLite3('budgetTracker.db');
            // Check if the database was created or opened successfully
            if ($db) {
                echo '<script>console.log("Database created/opened successfully!")</script>';
            } else {
                echo '<script>console.log("Failed to open/create the database.")</script>';
            }
            $select_query = "SELECT * FROM Payment";
            $result = $db->query($select_query);
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                if($row['budgetID'] != $_GET['budgetID']){
                    echo '<option value="' . $row['paymentID'] . '">' . $row['name'] . ' ' . $row['date'] .'</option>';
                }
            }
            ?>
        </select>
        <button onclick="assocNextPg()" style="margin-top:5px;">Next</button>
    </div>
    <div id="assoc-pg2" style="display: none;flex-direction: column;">
        <iframe id="assocOutputArea"></iframe>
        <button onclick="finishAssoc()" style="margin-top: 5px;">Done</button>
    </div>
</dialog>
</body>
