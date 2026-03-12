// JavaScript function to load the navbar
function loadNavbar() {
    // Get the current URL
    let currURL = window.location.href;
    let URLobj = new URL(currURL);

    // Check if there are any search parameters
    if (URLobj.searchParams.size > 0) {
        // Remove all search parameters
        URLobj.search = ""; // This clears all query parameters
    }

    // Convert the modified URL back to a string
    let thisURL = URLobj.toString();

    // Logic to fetch the appropriate sidebar based on the URL
    if (thisURL.endsWith("index.php")) {
        fetch("sidebar-hs.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else if (thisURL.endsWith("accounts.php")) {
        fetch("sidebar-as.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else if (thisURL.endsWith("budgets.php")) {
        fetch("sidebar-bs.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else if (thisURL.endsWith("goals.php")) {
        fetch("sidebar-gs.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else if (thisURL.endsWith("payments.php")) {
        fetch("sidebar-ps.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else if (thisURL.endsWith("savings.php")) {
        fetch("sidebar-ss.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    } else {
        fetch("sidebar.html")
            .then((response) => response.text())
            .then((data) => {
                document.getElementById("sidebar-container").innerHTML = data;
            })
            .catch((error) => console.error("Error loading navbar:", error));
    }
}
