<?php
include_once "internal/ui.php";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Staff login</title>
	</head>

	<body>
		<?php
			if (isset($_GET["result"]))
				echo $_GET["result"];

			(new PostForm("/internal/actions/staff_login.php", "POST"))
				->add_text_field("Username", "username")
				->add_password_field("Password", "password")
				->finalize();
		?>
	</body>
</html>
