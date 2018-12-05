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

    $query = "INSERT INTO Store (city, state, zip, phone_num) SELECT '$city', '$state', $zip, '$phone'
	      FROM dual WHERE NOT EXISTS (SELECT * FROM Store WHERE Store.phone_num = '$phone')";
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
    <select name="state">
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
    </select>
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
