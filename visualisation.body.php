		<div id="background" ></div>
		<div id='view'>
			<span id='index-bar' class='bar'>
				<?php
					$entries = $manager -> getEntryAll();				
					drawIndex(((isset($_GET['letter']))?$_GET['letter']:null));
				?>
			</span>
			<span id='left-pan' class='panel'></span>
			<span id='bottom-pan' class='panel'></span>
			<canvas></canvas>
		</div>
		<div id="test"></div>