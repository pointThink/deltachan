<div id=topbar>
	<p>[</p>
	<a href=/>home</a>
	<a href=/rules.php>rules</a>
	<p>]</p>

	<p>[</p>
	<?php
		include_once "internal/board.php";

		$boards = board_list();

		foreach ($boards as $b)
		{
			echo "<a href=/$b->id/>$b->id</a>";
		}
	?>
	<p>]</p>
</div>
