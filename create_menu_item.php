<!DOCTYPE HTML>  
<html>
<head>
<style>
/* Add a black background color to the top navigation */
.topnav {
    background-color: #333;
    overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav a {
    float: left;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
    background-color: #ddd;
    color: black;
}

/* Add a color to the active/current link */
.topnav a.active {
    background-color: #4CAF50;
    color: white;
}

/* error color */
.error {color: #FF0000;}
</style>
</head>
<body>

<h1>Restaurant Ordering Application</h1>

<div class="topnav">
  <a href="home.php">Home</a>
  <a class="active" href="#create_menu_item">Create Menu Item</a>
  <a href="order_meal.php">Order Meal</a>
  <a href="about.php">About</a>
</div>


<?php

// Create database connection
$conn = oci_connect('andmason', 'May301996', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Define variables
$miNameErr = "";	// menu item name error
$miName = "";		// menu item name

$miPriceErr = "";	// menu item price error
$miPrice = "";		// menu item price

$sideNameErr = "";	// side name error
$sideName = "";		// side name


// Menu item validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate menu item name
    if (empty($_POST["miName"])) {
        $miNameErr = "Item name is required";
	$miName = NULL;
    }
    else {
        $miName = test_input($_POST["miName"]);
    // check if menu item name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$miName)) {
        $miNameErr = "Only letters and white space allowed";
 	$miName = NULL;
    }
    else {
        $miNameErr = "";
    }
    }

    // Validate menu item price
    if (empty($_POST["miPrice"])) {
        $miPriceErr = "Item price is required, even if 0.00";
	$miPrice = NULL;
    }
    else {
        $miPrice = test_input($_POST["miPrice"]);

    // check if menu item price is a valid number
    if (filter_var($miPrice, FILTER_VALIDATE_FLOAT) === false) {
        $miPriceErr = "Please enter a number";
	$miPrice = NULL; 
    }
    else {
        $miPriceErr = "";
    }
    }

    if (empty($_POST["sideName"])) {
        $sideNameErr = "Side name is required";
	$sideName = NULL;
    }
    else {
        $sideName = test_input($_POST["sideName"]);

    // check if side name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$sideName)) {
        $sideNameErr = "Only letters and white space allowed";
 	$sideName = NULL;
    }
    else {
        $sideNameErr = "";
    }
    }



//select Menu_Item.item_name, Side.side_name from Menu_Item INNER JOIN Side ON Menu_Item.menu_item_id = Side.side_id;

//if menu item name exists && side name exists => do nothing  
echo $miName . '    ' . $miPrice;
echo '<br/>';
echo $sideName;
//select * from Menu_Item where Menu_Item.item_name = '$miName'";


$query = "insert into Menu_Item (item_name, item_price) SELECT '$miName', '$miPrice' FROM dual WHERE NOT EXISTS (select * from Menu_Item where Menu_Item.item_name = '$miName')";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

$query = 'COMMIT';
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

$query = "insert into Side (side_name) SELECT '$sideName' FROM dual WHERE NOT EXISTS (select * from Side where Side.side_name = '$sideName')";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

$query = 'COMMIT';
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

$query = "insert into Menu_Item_Side (menu_item_id, side_id) SELECT (Select menu_item_id from Menu_Item where Menu_Item.item_name = '$miName') , (Select side_id from Side where Side.side_name = '$sideName') FROM dual WHERE NOT EXISTS (select * from Menu_Item_Side where Menu_Item_Side.menu_item_id = (Select menu_item_id from Menu_Item where Menu_Item.item_name = '$miName') AND Menu_Item_Side.side_id = (Select side_id from Side where Side.side_name = '$sideName'))";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

$query = 'COMMIT';
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);


/*
//iterate through each row
while ($row = oci_fetch_array($stid,OCI_ASSOC)) 
{
    //iterate through each item in the row and echo it  
    foreach ($row as $item)    
    {
        echo $item.' ';
    }   
echo '<br/>';}
*/



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

<h2>Create Menu Item</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    Enter menu item name: <input type="text" name="miName">
    <span class="error">* <?php echo $miNameErr;?></span>
    <br><br>

    Enter menu item price: <input type="number" step="0.01" name="miPrice">
    <span class="error">* <?php echo $miPriceErr;?></span>
    <br><br>

    Enter side name: <input type="text" name="sideName">
    <span class="error">* <?php echo $sideNameErr;?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
    <p><span class="error">* required field</span></p>

</form>


</body>
</html>
