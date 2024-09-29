<div id=topbar>
	<a href=/>home</a>
	<a href=/rules.php>rules</a>

	<p>|</p>

	<?php
		include_once "internal/database.php";
	
		$db = new Database();
		$boards = $db->get_boards();

		foreach ($boards as $b)
			echo "<a href=/$b->id/>$b->id</a>";
	?>
</div>
