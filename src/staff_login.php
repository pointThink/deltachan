<?php
include_once "internal/ui.php";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Staff login</title>
		<?php include "internal/link_css.php"; ?>
	</head>

	<body>
		<?php
			include "topbar.php";
		
			if (isset($_GET["result"]))
			{
				echo $_GET["result"];

				if ($_GET["result"] == "success")
				{
					header("Location: /staff_dashboard.php");
					die();
				}
			}
			
			echo "<div class=post_form>";
			(new PostForm("/internal/actions/staff_login.php", "POST"))
				->add_text_field("Username", "username")
				->add_password_field("Password", "password")
				->finalize();
			echo "</div>";
		?>
	</body>
</html>
