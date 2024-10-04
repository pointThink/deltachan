<?php
include_once "../../database.php";
include_once "../../staff_session.php";
include_once "../../ui.php";

if (!staff_session_is_valid() || !staff_is_admin()) 
	die("You are not allowed here");

if (count($_POST) > 0)
{
	$database = new Database();
	$database->delete_staff_account($_POST["username"]);
	header("Location: /internal/staff_forms/manage_accounts.php");
	die();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Account deletion</title>
		<?php include "../../link_css.php" ?>
	</head>

	<body>
		<?php include "../../../topbar.php" ?>

		<?php
		echo "<h1 class=title>Are sure you want to delete " . $_GET["username"] . "</h1>";
		echo "<h4 class=title>This action cannot be reverted</h4>";
		?>

		<form action="" class=title method=POST>
			<?php echo "<input type=hidden name=username value=" . $_GET["username"] . ">" ?>
			<button type=submit>Yes</button>
		</form>
		
		<?php include "../../../footer.php" ?>
	</body>
</html>
