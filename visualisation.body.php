		<div id='view'>
			<span id='index-bar' class='bar'>
				<?php
					$entries = $manager -> getEntryAll();				
					drawIndex();
				?>
			</span>
			<span id='left-pan' class='panel'></span>
			<span id='bottom-pan' class='panel'></span>
			<canvas></canvas>
		</div>