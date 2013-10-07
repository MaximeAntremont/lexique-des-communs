<?php if( isset($_SESSION['lexique_attr']) && isset($_SESSION['lexique_name']) ){ ?>
	<canvas id="canvas" ></canvas>

	<div id="top_panel">
		<div id="index" class="panel_button" ></div>
		
		<div id="add_ressource" class="panel_button" ></div>
	</div>
	<div id="left_panel">
		<div id="addZoom" class="panel_button" ></div>
		<div id="subZoom" class="panel_button" ></div>
	</div>
	<div id="right_panel">
		<div id="addTrend" class="panel_button" >+</div>
		<div id="subTrend" class="panel_button" >-</div>
		<div id="addAlert" class="panel_button" >!</div>
		<div id="showLinks" class="panel_button" ><div></div></div>
	</div>
	<div id="bottom_panel">
		<div id="alertBug" class="panel_button" style="margin-left: 40px;border-left: 1px solid rgb(200,200,200);"></div>
		<div id="infos" class="panel_button">v. a2r03</div>
		<div id="share" class="panel_button">Partager</div>
		<div>Lexique: <?php echo $_SESSION['lexique_name']; ?></div>
	</div>
	
	<div id="top_left_corner">
		<div id="part_one" ></div>
		<div id="part_two" ></div>
	</div>
	<div id="top_right_corner">
		<div id="type" ></div>
		<div id="category" ></div>
		<div id="val" ></div>
	</div>
	
	<div id="cache_panel" >
		<div style="height: 100%;vertical-align: middle;display: inline-block;" ></div>
		<div id="middle_window">
			<div id="header"></div>
			<div id="content"></div>
			<div id="footer"></div>
		</div>
	</div>
	
	<div id="entrys" >
		<div class="close">X</div>
		<div id="add_entry" class="panel_button" ></div>
		<div class="content"></div>
	</div>
	
	<?php }else header('Location: index.php'); ?>