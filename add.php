<?php
include 'htmlstart.html';
include 'db.php';
include 'functions.php';
//Sätt in i databasen innan menyn laddas

if(isset($_POST['name'])){
	//Kolla antal rader
	$sql="SELECT name,cat_id FROM categories";
	if ($result=$link->prepare($sql)){
		$result->execute();
		$result->store_result();
		$num_rows = $result->num_rows;
		
	}else{
		die("MySQL error");
	}
	//Formverifikation
	if($_POST['name']==''){
		JSerror("Your string is empty.");
	}else if(strlen($_POST['name'])<2||strlen($_POST['name'])>10){
		JSerror("Your string is either too long or too short.");
	}else if($num_rows == 10){
		JSerror("Maximum amount of categories reached. Delete a category to make space.");
	}else{
		$link->query("INSERT INTO categories(name) VALUES ('$_POST[name]')");
		order_categories($link);
	}

}
if(isset($_POST['removeName'])){
	$link->query("DELETE FROM categories WHERE name='$_POST[removeName]'");
	header("Refresh:0");
	}
include 'menu.php';
include 'sidemenu.php';



$get = $_GET['item'];
echo "<div class='content'><p class='text'>";
?>
<div class='content'><p class='text'>
<div class="notif error">
  <h2>Error!</h2>
  <p class='notifText'></p>
</div></p>
<?php
switch($get){
	case "menu":
		echo "<div class='form-style-2'>
		<div class='form-style-2-heading'>Add a category to the top menu</div>
		<form action='' method='post' class='validateForm'>
		<label for='field1'><span name='field1'>Name <span class='required'>*</span></span><input type='text' class='input-field' name='name' placeholder='(2-10 characters)' /></label>
		<label><span>&nbsp;</span><input type='submit' value='Submit' /></label>
		</form>
		</div>";
		echo "<div class='form-style-2'>
		<div class='form-style-2-heading'>Remove a category from the top menu</div>
		<form action='' method='post' class='validateForm'>
		<label for='field4'><span>Regarding <span class='required'>*</span></span><select name='removeName' class='select-field'>";

		$sql="SELECT name,cat_id FROM categories";

		if ($result=$link->query($sql))
		{
			while ($row=mysqli_fetch_row($result))
			{	
				echo "<option value='$row[0]'>$row[1]. $row[0]</option>\n\t";
			}
		}

		echo "</select></label>
		<label><span>&nbsp;</span><input type='submit' value='Delete' /></label>
		</form>
		</div>";
		break;
	case "cat":
		if(!isset($_SESSION['categoryName'])){die("Error");}
		echo "<div class='form-style-2'>
		<div class='form-style-2-heading'>Add a subcategory for $_SESSION[categoryName]</div>
		<form action='' method='post' class='validateForm'>
		<label for='field1'><span name='field1'>Name <span class='required'>*</span></span><input type='text' class='input-field' name='name' placeholder='(2-10 characters)' /></label>
		<label><span>&nbsp;</span><input type='submit' value='Submit' /></label>
		</form>
		</div>";
		echo "<div class='form-style-2'>
		<div class='form-style-2-heading'>Remove a subcategory from $_SESSION[categoryName]</div>
		<form action='' method='post' class='validateForm'>
		<label for='field4'><span>Regarding <span class='required'>*</span></span><select name='removeName' class='select-field'>";

		$sql="SELECT name,cat_id FROM categories";

		if ($result=$link->query($sql))
		{
			while ($row=mysqli_fetch_row($result))
			{	
				echo "<option value='$row[0]'>$row[1]. $row[0]</option>\n\t";
			}
		}

		echo "</select></label>
		<label><span>&nbsp;</span><input type='submit' value='Delete' /></label>
		</form>
		</div>";
		break;
	default:
		header('HTTP/1.0 403 Forbidden');
		break;
	
	}
?>	

</form>
</div>