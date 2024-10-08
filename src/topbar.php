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

	<div id="theme_selector_section">
		<p>Theme:</p>
		<script src="/internal/theme_selector.js"></script>

		<select onchange="selectThemeFromPicker();" id="theme_selector" autocomplete="off">
		<?php
			$themes = scandir(__DIR__ . "/internal/styles/");

			foreach ($themes as $theme)
			{
				if (($theme != "." && $theme != "..") || $theme == "custom" || $theme == "default")
				{
					if (!isset($_COOKIE["theme"]) && $theme == "default")
						echo "<option selected value='" . $theme . "'>" . $theme . "</option>";
					else if (isset($_COOKIE["theme"]) && $_COOKIE["theme"] == $theme)
						echo "<option selected value='" . $theme . "'>" . $theme . "</option>";
					else
						echo "<option value='" . $theme . "'>" . $theme . "</option>";
				}
			}
		?>
		</select>
	</div>
</div>
