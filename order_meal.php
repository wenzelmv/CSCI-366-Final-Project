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
  <a href="store.php">Add Store</a>
  <a class="active" href="order_meal.php">Order Meal</a>
  <a href="about.php">About</a>

</div>

<?php

echo "<br>";
// Create database connection
$conn = oci_connect('andmason', 'May301996', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
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

?>


<h2>Order Meal</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    Select Menu Item: <input type="text" name="miName">
    <span class="error">* <?php echo $miNameErr;?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
    <p><span class="error">* required field</span></p>

</form>


</body>
</html>
