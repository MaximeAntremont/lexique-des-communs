
	<canvas id="canvas" ></canvas>

	<div id="top_panel"></div>
	<div id="left_panel"></div>
	<div id="right_panel"></div>
	<div id="bottom_panel"></div>
	<div id="top_left_corner">
		<?php
			$entries = $manager -> getEntryAll();				
			drawIndex(((isset($_GET['letter']))?$_GET['letter']:null));
		?>
	</div>


<!--		<div id='view'>
			<span id='index-bar' class='bar'> -->
<!--		</span>
			<span id='left-pan' class='panel'></span>
			<span id='bottom-pan' class='panel'></span>
		</div>
		<div id="test"></div> -->