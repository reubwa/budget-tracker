
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
    function toggleDS(){
        if(document.getElementById('ds').style.display == 'none'){
            document.getElementById('ds').style.display = 'flex'
        }else{
            document.getElementById('ds').style.display = 'none'
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
            <h1 class="list-header">Payments</h1>
            <div class="tb-btn-group">
                <span class="material-symbols-rounded" id="tb-btn" onclick="toggleCol1()">space_dashboard</span>
                <span class="material-symbols-rounded" id="tb-btn" onclick="toggleChartCol()">show_chart</span>
                <span class="material-symbols-rounded" id="tb-btn" onclick="document.getElementById('newPayment').showModal()">add</span>
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
                    if(isset($_GET['category'])&&isset($_GET['startDate'])&&isset($_GET['endDate'])){
                        $selectedCategory = $_GET['category'];
                        $startDate = $_GET['startDate'];
                        $endDate = $_GET['endDate'];
                        $startDate = htmlspecialchars(trim($_GET['startDate']), ENT_QUOTES, 'UTF-8');
                        $endDate = htmlspecialchars(trim($_GET['endDate']), ENT_QUOTES, 'UTF-8');
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE category = "."'"."$selectedCategory"."' AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'";
                        $select_query = "SELECT * FROM Payment WHERE category = "."'"."$selectedCategory"."' AND date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'"." LIMIT $offset, $records_per_page";
                    }else if(isset($_GET['category'])){
                        $selectedCategory = $_GET['category'];
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE category = "."'"."$selectedCategory"."'";
                        $select_query = "SELECT * FROM Payment WHERE category = "."'"."$selectedCategory"."'"." LIMIT $offset, $records_per_page";
                    }else if(isset($_GET['startDate'])&&isset($_GET['endDate'])){
                        $startDate = $_GET['startDate'];
                        $endDate = $_GET['endDate'];
                        $startDate = htmlspecialchars(trim($_GET['startDate']), ENT_QUOTES, 'UTF-8');
                        $endDate = htmlspecialchars(trim($_GET['endDate']), ENT_QUOTES, 'UTF-8');
                        $total_query = "SELECT COUNT(*) as total FROM Payment WHERE date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'";
                        $select_query = "SELECT * FROM Payment WHERE date BETWEEN "."'"."$startDate"."'"." AND "."'"."$endDate"."'"." LIMIT $offset, $records_per_page";
                    }else{
                        $total_query = "SELECT COUNT(*) as total FROM Payment";
                        $select_query = "SELECT * FROM Payment LIMIT $offset, $records_per_page";
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
                    echo '<td colspan="2"></td>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        $bookingID = $row['paymentID'];
                        echo '<tr><td>' . $row['paymentID'] . '</td>' . '<td>' . $row['name'] . '</td>' . '<td>' . $row['category'] . '</td>' . '<td>' . $row['date'] . '</td>' . '<td>' . $row['cost'] .'</td>' . '<td><a href="inspect-account.php?accountID=' . htmlspecialchars($row['accountID'], ENT_QUOTES, 'UTF-8') . '">' . $row['accountID'] . '</a></td>' . '<td><a href="inspect-budget.php?budgetID=' . htmlspecialchars($row['budgetID'], ENT_QUOTES, 'UTF-8') . '">' . $row['budgetID'] . '</a></td> <td><a onclick="raiseUpdate('.$bookingID.')" style="cursor: pointer;"><span class="material-symbols-rounded">edit</span></a></td> <td><a style="cursor: pointer;" onclick="deleteDialog('.$bookingID.')"><span class="material-symbols-rounded">delete</span></a></td> </tr>';
                        if(isset($_GET['category'])){
                            echo '<script type="text/JavaScript">cats.push("'.$row['name'].'")</script>';
                            echo '<script type="text/JavaScript">totals.push("'.$row['cost'].'")</script>';
                        }else{
                            echo '<script type="text/JavaScript">cats.push("'.$row['category'].'")</script>';
                            echo '<script type="text/JavaScript">totals.push("'.$row['cost'].'")</script>';
                        }
                        echo '<script type="text/JavaScript">dates.push("'.$row['date'].'")</script>';
                        echo '<script type="text/JavaScript">dateTots.push("'.$row['cost'].'")</script>';
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
                    <canvas id="dateChart" style="width:100%;max-width:700px;max-height: 700px;height: 200px;"></canvas>
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
    function nextPage(){
        document.getElementById('newPayment-pg1').style.display = 'none';
        document.getElementById('newPayment-pg2').style.display = 'flex';
    }
    function completeDialog(){
        document.getElementById('newPayment').close();
        location.reload();
    }
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
</script>
<dialog id="newPayment">
    <div id="newPayment-pg1">
        <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('newPayment').close()">close</span>
        <div class="fieldset">
            <form action="payment-new.php" method="post" autocomplete="on" target="outputArea" onsubmit="nextPage()">
                <label for="name">Name</label>
                <input id="name" name="name" required/>
                <label for="category">Category</label>
                <input id="category" name="category" required/>
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required/>
                <label for="startingAmount">Cost (£)</label>
                <input type="number" id="cost" name="cost" step="0.01" required/>
                <label for="account">Account</label>
                <select name="accountID" id="accountID" required>
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
                        echo '<option value="' . $row['accountID'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <br/>
                <label for="budget" style="margin-top: 10px">Budget</label>
                <select name="budgetID" id="budgetID" required style="margin-top: 10px">
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
                        echo '<option value="' . $row['budgetID'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <input type="submit" style="margin-top: 10px">
                <input type="reset" style="margin-top: 10px">
            </form>
        </div>
    </div>
    <div id="newPayment-pg2" style="display: none;flex-direction: column;">
        <iframe name="outputArea"></iframe>
        <button onclick="completeDialog()" style="margin-top:5px;">Done</button>
    </div>
</dialog>
<dialog id="delPayment" style="display: none;flex-direction: column;">
    <iframe id="delOutputArea"></iframe>
    <button onclick="completeDeleteDialog()" style="margin-top:5px;">Done</button>
</dialog>
<dialog id="updPayment" style="display: none;flex-direction: column">
    <span class="material-symbols-rounded" style="cursor: pointer;" onclick="document.getElementById('updPayment').close();document.getElementById('updPayment').style.display = 'none';" id="upd-close">close</span>
    <iframe id="updOutputArea" onload="handoffHandler()" style="height: 580px;width:360px;"></iframe>
    <button onclick="finishUpdate()" style="margin-top:5px;display: none;" id="upd-done-btn">Done</button>
</dialog>
</body>
