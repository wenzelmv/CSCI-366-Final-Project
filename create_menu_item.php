<!DOCTYPE HTML>  
<html>
<head>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Restaurant Ordering Application</h1>

<div class="topnav">
  <a href="home.php">Home</a>
  <a class="active" href="#create_menu_item">Create Menu Item</a>
  <a href="servers.php">Servers</a>
  <a href="store.php">Add Store</a>
  <a href="order_meal.php">Order Meal</a>
  <a href="about.php">About</a>
</div>

<h2>Create Menu Item</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    Enter menu item name: <br/>
    <input type="text" name="miName">
    <span class="error">* <?php echo $miNameErr;?></span>
    <br><br>

    Enter menu item price: <br/>
    <input type="number" step="0.01" name="miPrice">
    <span class="error">* <?php echo $miPriceErr;?></span>
    <br><br>

    Enter side name: <br/>
    <input type="text" name="sideName">
    <span class="error">* <?php echo $sideNameErr;?></span>
    <br><br>

    <input type="submit" id="submit" name="submit" value="Submit">
    <p><span class="error">* required field</span></p>

</form>

<h2>Delete Menu Item</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    Enter menu item name to delete: <br/>
    <input type="text" name="miNameDelete">
    <span class="error">* <?php echo $miNameErrDelete;?></span>
    <br><br>

    Enter side name to delete: <br/>
    <input type="text" name="sideNameDelete">
    <span class="error">* <?php echo $sideNameErrDelete;?></span>
    <br><br>

    <input type="submit" id="delete" name="delete" value="Delete">
    <p><span class="error">* required field</span></p>

</form>


<?php

// Create database connection
$conn = oci_connect('miwenzel', 'Jul691997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
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

    if (isset($_POST['submit'])) {

    // Validate menu item name
    if (empty($_POST["miName"])) {
        $miNameErr = "Item name is required";
    }
    else {
        $miName = test_input($_POST["miName"]);
    }
    // check if menu item name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$miName)) {
        $miNameErr = "Only letters and white space allowed"; 
	$miName = NULL;
    }
    else {
        $miName = test_input($_POST["miName"]);
    }
    // Validate menu item price
    if (empty($_POST["miPrice"])) {
        $miPriceErr = "Item price is required, even if 0.00";
    }
    else {
        $miPrice = test_input($_POST["miPrice"]);
    }
    // check if menu item price is a valid number
    if (filter_var($miPrice, FILTER_VALIDATE_FLOAT) === false) {
        $miPriceErr = "Please enter a number";
	$miPrice = NULL; 
    }
    else {
        $miPriceErr = "";
    }
    if (empty($_POST["sideName"])) {
        $sideNameErr = "Side name is required";
	$sideName = NULL;
    }
    else {
        $sideName = test_input($_POST["sideName"]);
    }

    //select Menu_Item.item_name, Side.side_name from Menu_Item INNER JOIN Side ON Menu_Item.menu_item_id = Side.side_id;

    //if menu item name exists && side name exists => do nothing  
    echo $miName . '    ' . $miPrice;
    echo '<br/>';
    echo $sideName;
    //select * from Menu_Item where Menu_Item.item_name = '$miName'";

	// Insert into Menu_Item table
    $query = "insert into Menu_Item (item_name, item_price) SELECT '$miName', '$miPrice' FROM dual WHERE NOT EXISTS (select * from Menu_Item where Menu_Item.item_name = '$miName')";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    // Insert into Side table
    $query = "insert into Side (side_name) SELECT '$sideName' FROM dual WHERE NOT EXISTS (select * from Side where Side.side_name = '$sideName')";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

	// Insert into Menu_Item_Side
    $query = "insert into Menu_Item_Side (menu_item_id, side_id) SELECT (Select menu_item_id from Menu_Item where Menu_Item.item_name = '$miName') , (Select side_id from Side where Side.side_name = '$sideName') FROM dual WHERE NOT EXISTS (select * from Menu_Item_Side where Menu_Item_Side.menu_item_id = (Select menu_item_id from Menu_Item where Menu_Item.item_name = '$miName') AND Menu_Item_Side.side_id = (Select side_id from Side where Side.side_name = '$sideName'))";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);


    oci_free_statement($stid);
    oci_close($conn);

	}
}

?>


<?php

// Create database connection
$conn = oci_connect('miwenzel', 'Jul691997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Define variables
$miNameErrDelete = "";	// menu item name error
$miNameDelete = "";		// menu item name

$sideNameErrDelete = "";	// side name error
$sideNameDelete = "";		// side name


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['delete'])) {

    // Validate menu item name
    if (empty($_POST["miNameDelete"])) {
        $miNameErrDelete = "Item name is required";
    }
    else {
        $miNameDelete = test_input($_POST["miNameDelete"]);
    }
    // check if menu item name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$miNameDelete)) {
        $miNameErrDelete = "Only letters and white space allowed"; 
	$miNameDelete = NULL;
    }
    else {
        $miNameDelete = test_input($_POST["miNameDelete"]);
    }
  
    if (empty($_POST["sideNameDelete"])) {
        $sideNameErrDelete = "Side name is required";
	$sideNameDelete = NULL;
    }
    else {
        $sideNameDelete = test_input($_POST["sideNameDelete"]);
    }
  
    echo $miNameDelete;
    echo '<br/>';
    echo $sideNameDelete;
    //select * from Menu_Item where Menu_Item.item_name = '$miName'";


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
    /*
    $query = "SELECT * FROM Menu_Item";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    echo "<table border='1'>
    <tr>
    <th>Id</th>
    <th>Item Name</th>
    <th>Item Price</th>
    </tr>";

    while ($row = oci_fetch_array($stid,OCI_NUM)) {
    	echo "<tr>";
	echo "<td>" . $row[0] . "</td>";
	echo "<td>" . $row[1] . "</td>";
	echo "<td>" . $row[2] . "</td>";
	echo "</tr>";
    }
    echo "</table>";
    */

    $query = "DELETE FROM (SELECT item_name, item_price, side_name FROM Menu_Item_Side INNER JOIN Menu_Item ON Menu_Item.menu_item_id = Menu_Item_Side.menu_item_id INNER JOIN Side ON Side.side_id = Menu_Item_Side.side_id) WHERE item_name = '$miNameDelete' AND side_name = '$sideNameDelete'";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    $query = 'COMMIT';
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    oci_free_statement($stid);
    oci_close($conn);

	}
}
?>

<?php
// Create database connection
$conn = oci_connect('miwenzel', 'Jul691997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
  
    $query = "SELECT item_name, item_price, side_name FROM Menu_Item_Side INNER JOIN Menu_Item ON Menu_Item.menu_item_id = Menu_Item_Side.menu_item_id INNER JOIN Side ON Side.side_id = Menu_Item_Side.side_id ORDER BY item_name ASC";
    $stid = oci_parse($conn,$query);
    oci_execute($stid,OCI_DEFAULT);

    echo "<table border='1'>
    <tr>
    <th>Item Name</th>
    <th>Item Price</th>
    <th>Side Name</th>
    </tr>";

    while ($row = oci_fetch_array($stid,OCI_NUM)) {
		echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "</tr>";
    }
    echo "</table>";


    oci_free_statement($stid);
    oci_close($conn);

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


</body>
</html>
