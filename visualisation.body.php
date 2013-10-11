<?php if( isset($_SESSION['lexique_attr']) && isset($_SESSION['lexique_name']) ){ ?>
	<canvas id="canvas" ></canvas>

	<div id="top_panel">
		<a href="index.php"><div id="retourIndex" class="panel_button" >Portail</div></a>
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
		<div id="infos" class="panel_button">v. a3r00</div>
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

	<div id="bottom_left_corner">
		<ul id="icons_list">
			<li><span class="icon text"></span><span class="label">Longs textes</span></li>
			<li><span class="icon img"></span><span class="label">Images</span></li>
			<li><span class="icon video"></span><span class="label">Vidéos</span></li>
			<li><span class="icon sound"></span><span class="label">Sons</span></li>
			<li><span class="icon link"></span><span class="label">Liens</span></li>
		</ul>

		<ul id="link_list">
			<li><span class="link ok"></span><span class="legend">accord</span><span class="legend last">désaccord</span><span class="legend neutral">neutre</span></li>
			<li><span class="link include"></span><span class="legend">inclu dans</span><span class="legend last">exclu de</span><span class="legend neutral">neutre</span></li>
			<li><span class="link conflict"></span><span class="legend conflict">conflictuel</span></li>
		</ul>
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