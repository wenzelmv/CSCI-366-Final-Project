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
  <a href="servers.php">Servers</a>
  <a class="active" href="#store">Add Store</a>
<<<<<<< HEAD
=======
  <a href="order_meal.php">Order Meal</a>
>>>>>>> 036b5e8e6c726be2707c29210e5d6c9840e8976f
  <a href="about.php">About</a>
</div>


<?php

// Create database connection
$conn = oci_connect('miwenzel', 'Jul691997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Define variables
$city = "";
$cityErr = "";

$state = "";
$stateErr = "";

$zip = "";
$zipErr = "";

$phone = "";
$phoneErr = "";


// Menu item validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate city
    if (empty($_POST["city"])) {
        $cityErr = "City is required";
    }
    else {
        $city = test_input($_POST["city"]);
    }
    // Check if city only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$city)) {
        $cityErr = "Only letters and white space allowed"; 
	$city = NULL;
    }
    else {
        $city = test_input($_POST["city"]);
    }

    // Validate state
    if (empty($_POST["state"])) {
        $stateErr = "State is required";
    }
    else {
        $state = test_input($_POST["state"]);
    }
    // Check if state only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$state)) {
        $stateErr = "Only letters and white space allowed"; 
	$state = NULL;
    }
    else {
        $state = test_input($_POST["state"]);
    }

    // Validate zip code
    if (empty($_POST["zip"])) {
        $zipErr = "Zip code is required";
    }
    else {
        $zip = test_input($_POST["zip"]);
    }
    // check if zip code is a number
    if (!is_numeric($zip)) {
        $zipErr = "Please enter a number"; 
	$zip = NULL;
    }
    else {
        $zip = test_input($_POST["zip"]);
    }

    // Validate phone number
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    }
    else {
        $phone = test_input($_POST["phone"]);
    }

    echo $city;
    echo '<br/>';
    echo $state;
    echo '<br/>';
    echo $zip;
    echo '<br/>';
    echo $phone;

    $query = "INSERT INTO Store(city, state, zip, phone_num) VALUES ('$city', '$state', $zip, '$phone')";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    // Displaying server table  
    $query = "SELECT * FROM Store";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    echo "<h2>Stores</h2>";

    echo "<table border='1'>
    <tr>
    <th>Store ID</th>
    <th>Location</th>
    <th>Phone Number</th>
    </tr>";

    while ($row = oci_fetch_array($stid,OCI_NUM)) {
    	echo "<tr>";
	echo "<td>" . $row[0] . "</td>";
	echo "<td>" . $row[1] . ", " . $row[2] . " " . $row[3] . "</td>";
	echo "<td>" . $row[4] . "</td>";
	echo "</tr>";
    }
    echo "</table>";
    oci_free_statement($stid);
    oci_close($conn);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Add Store</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    City: <br/>
    <input type="text" name="city">
    <span class="error">* <?php echo $cityErr;?></span>
    <br><br>

    State: <br/>
    <input type="text" name="state">
    <span class="error">* <?php echo $stateErr;?></span>
    <br><br>

    Zip code: <br/>
    <input type="number" name="zip">
    <span class="error">* <?php echo $zipErr;?></span>
    <br><br>

    Phone number: <br/> 
    <input type="text" name="phone">
    <span class="error">* <?php echo $phoneErr;?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
    <p><span class="error">* required field</span></p>
</form>

</body>
</html>
