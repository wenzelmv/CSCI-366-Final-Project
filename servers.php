<!DOCTYPE HTML>  
<html>
<head>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Restaurant Ordering Application</h1>

<div class="topnav">
  <a href="home.php">Home</a>
  <a href="create_menu_item.php">Create Menu Item</a>
  <a class="active" href="#servers">Servers</a>
  <a href="store.php">Add Store</a>
  <a href="order_meal.php">Order Meal</a>
  <a href="about.php">About</a>
</div>


<?php

// Create database connection
$conn = oci_connect('miwenzel', 'Jul691997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Define variables

$eNumErr = "";
$eNum = "";

$sFirstNameErr = "";   	// server first name error
$sFirstName = "";	// server name

$sLastNameErr = "";   	// server last name error
$sLastName = "";	// server last name

$sDateErr = "";
$sDate = "";

$storeIDErr = "";
$storeID = "";


// Menu item validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate employee number
    if (empty($_POST["eNum"])) {
        $eNumErr = "Employee number is required";
    }
    else {
        $eNum = test_input($_POST["eNum"]);
    }
    // check if employee number is a number
    if (!is_numeric($eNum)) {
        $eNumErr = "Please enter a number"; 
	$eNum = NULL;
    }
    else {
        $eNum = test_input($_POST["eNum"]);
    }

    // Validate server first name
    if (empty($_POST["sFirstName"])) {
        $sFirstNameErr = "Last name is required";
    }
    else {
        $sFirstName = test_input($_POST["sFirstName"]);
    }
    // check if server first name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$sFirstName)) {
        $sFirstNameErr = "Only letters and white space allowed"; 
	$sFirstName = NULL;
    }
    else {
        $sFirstName = test_input($_POST["sFirstName"]);
    }

    // Validate server last name
    if (empty($_POST["sLastName"])) {
        $sLastNameErr = "Last name is required";
    }
    else {
        $sLastName = test_input($_POST["sLastName"]);
    }
    // check if server last name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$sLastName)) {
        $sLastNameErr = "Only letters and white space allowed"; 
	$sLastName = NULL;
    }
    else {
        $sLastName = test_input($_POST["sLastName"]);
    }

    // Validate start date 
    if (empty($_POST["sDate"])) {
        $sDateErr = "Start date is required";
    }
    else {
        $sDate = test_input($_POST["sDate"]);
    }

    // Validate store ID number
    if (empty($_POST["storeID"])) {
        $storeIDErr = "Store ID is required";
    }
    else {
        $storeID = test_input($_POST["storeID"]);
    }
    // check if employee number is a number
    if (!is_numeric($storeID)) {
        $storeIDErr = "Please enter a number"; 
	$storeID = NULL;
    }
    else {
        $storeID = test_input($_POST["storeID"]);
    }

    echo $eNum;
    echo '<br/>';
    echo $sFirstName . ' ' . $sLastName;
    echo '<br/>';
    echo $sDate;
    echo '<br/>';
    echo $storeID;

    $query = "INSERT INTO Server(employee_num, first_name, last_name, start_date, store_id) 
	      VALUES ($eNum, '$sFirstName', '$sLastName', TO_DATE('$sDate', 'YYYY-MM-DD'), $storeID)";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    // Displaying server table  
    $query = "SELECT * FROM Server";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    echo "<h2>Current Servers</h2>";

    echo "<table border='1'>
    <tr>
    <th>Server ID</th>
    <th>Employee Number</th>
    <th>Name</th>
    <th>Start Date</th>
    <th>Store ID</th>
    </tr>";

    while ($row = oci_fetch_array($stid,OCI_NUM)) {
    	echo "<tr>";
	echo "<td>" . $row[0] . "</td>";
	echo "<td>" . $row[1] . "</td>";
	echo "<td>" . $row[3] . ", " . $row[2] . "</td>";
	echo "<td>" . $row[4] . "</td>";
	echo "<td>" . $row[5] . "</td>";
	echo "</tr>";
    }
    echo "</table>";
    oci_free_statement($stid);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Add Server</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    Enter employee number: <br/>
    <input type="number" name="eNum">
    <span class="error">* <?php echo $eNumErr;?></span>
    <br><br>

    Enter server first name: <br/>
    <input type="text" name="sFirstName">
    <span class="error">* <?php echo $sFirstNameErr;?></span>
    <br><br>

    Enter server last name: <br/>
    <input type="text" name="sLastName">
    <span class="error">* <?php echo $sLastNameErr;?></span>
    <br><br>

    Enter server start date: <br/> 
    <input type="date" name="sDate">
    <span class="error">* <?php echo $sDateErr;?></span>
    <br><br>

    Enter store ID: <br/>
    <input type="text" name="storeID">
    <span class="error">* <?php echo $storeIDErr;?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
    <p><span class="error">* required field</span></p>
</form>

<h2>Delete Server</h2>

<?php
    // Displaying store table  
    $query = "SELECT * FROM Store";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    echo "<h2>Active Stores</h2>";

    echo "<table border='1'>
    <tr>
    <th>Store ID</th>
    <th>City</th>
    <th>State</th>
    <th>Zip Code</th>
    <th>Phone Number</th>
    </tr>";

    while ($row = oci_fetch_array($stid,OCI_NUM)) {
    	echo "<tr>";
	echo "<td>" . $row[0] . "</td>";
	echo "<td>" . $row[1] . "</td>";
	echo "<td>" . $row[2] . "</td>";
	echo "<td>" . $row[3] . "</td>";
	echo "<td>" . $row[4] . "</td>";
	echo "</tr>";
    }
    echo "</table>";
    oci_free_statement($stid);

    oci_close($conn);
?>

</body>
</html>
