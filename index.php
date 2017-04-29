<?php 
include "config.php";
?><!DOCTYPE html>
<html>
<head>
	<title>IP-count.ries</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Oros">
	<meta name="description" content="Source code : https://github.com/Oros42/IP-count.ries_interface">
	<style type="text/css">
		body{
			background-color: black;
			color: #AAA;
		}
		#flex{
			display: flex;
			flex-flow: wrap;
		}
		.tables{
			margin-left: 20px;
			margin-bottom: 20px;
		}
		table{
			font-size: 16px;
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			line-height: 1.5;
			border-collapse: collapse;
		}
		th {
			background-color: #107314;
			color: #C7C7C7;
			padding: 8px;
			border: 1px solid #636363;
		}
		th:nth-child(1){
			min-width: 30px;
		}
		th:nth-child(2){
			min-width: 60px;
		}
		th:nth-child(3){
			min-width: 180px;
		}
		th:nth-child(4){
			min-width: 80px;
		}
		tr:nth-child(even) {
			background-color: #3B3B3B;
		}
		td{
			border: 1px solid #636363;
			text-align: left;
			padding: 8px;
		}
		.map_contener{
			width: 416px;
			margin-left: 0px;
			cursor:pointer;
		}
	</style>
	<?php if(!empty($html_head)){
		echo $html_head;
	}
?>
</head>
<body>
<?php if(!empty($html_body)){
	echo $html_body;
}?>
<div id="flex">
<?php
$num_table=0;
$tables_data=array();
foreach ($countries_files as $countries_file => $title) {
?>

		<table id="table_<?php echo $num_table; ?>" class="tables">
		<tr>
			<th colspan='4'><?php echo htmlentities($title); ?></th>
		</tr>
		<tr>
			<td id="div_map_<?php echo $num_table; ?>" class="map_contener" colspan='4'></td>
		</tr>
		<tr>
			<th>N°</th>
			<th>Code</th>
			<th>Country</th>
			<th>Nb IP</th>
		</tr>
<?php
	if(is_file($countries_file)){
		$file = fopen($countries_file,"r");
		$cpt=0;
		$ip_cpt=0;
		while(! feof($file)) {
			$c=fgetcsv($file);
			if($c[0]!="" && count($c)==3){
				$cpt++;
				$ip_cpt+=(int) $c[0];
				echo "		<tr><td>".$cpt."</td><td>".$c[1]."</td><td>".trim($c[2])."</td><td>".$c[0]."</td></tr>\n";
				$tables_data[$num_table][$c[1]]=$c[0];
			}
		}
		fclose($file);
		echo "		<tr><td colspan='3'>Total IP</td><td>".$ip_cpt."</td>\n";
	}else{
		echo "		<tr><td colspan='4'>Error : can't open ".htmlentities($countries_file)."</td>\n";
	}
	?>
		</table>
		<!--<div  id="div_map_<?php echo $num_table; ?>" class="map_contener"></div>-->
	
<?php
	$num_table++;
}
?>
</div>
<noscript>Need Javascript for map</noscript>
<script type="text/javascript">
function zoom(evt) {
	var map_id=evt.currentTarget.map_id;
	map=document.getElementById(map_id).children[0];
	if("position" in map.style && map.style.position == "fixed"){
		// close zoom
		map.setAttribute('style', '');
	}else{
		// open zoom
		map.setAttribute('style', 'position:fixed;width:100%;height:100%;z-index:1000;top:0;left:0;right:0;bottom:0;background-color:black;');
	}
}

function loadsvg(){
	var xhr = new XMLHttpRequest();
	/* Map worldLow.svg from https://www.amcharts.com/svg-maps/?map=world
		add 'viewBox="0 0 1008 651" preserveAspectRatio="xMidYMid meet"' to the svg tag
	*/
	xhr.open('GET', 'worldLow.svg');
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4){
			var svg=xhr.responseXML.documentElement;
			var tables = document.getElementsByClassName("tables");
			for (var i = 0, end = tables.length; i < end; i++) {
				var svg2 = document.importNode(svg,true);
				var trs=tables[i].getElementsByTagName('tr');
				for (var t = 3, end2=trs.length-1; t < end2; t++) {
					// add color to countries
					var nb_ip=trs[t].children[3].textContent;
					if(nb_ip<10){
						c='#FF0000';
					}else if(nb_ip<100){
						c='#DF0000';
					}else if(nb_ip<500){
						c='#CF0000';
					}else if(nb_ip<1000){
						c='#AF0000';
					}else{
						c='#8F0000';
					}
					var country = svg2.getElementById(trs[t].children[1].textContent);
					if(country!=null){
						country.setAttribute('style','fill:'+c);
					}
				}
				svg2.addEventListener('click', zoom, false);
				svg2.map_id='div_map_'+i;
				// add svg map
				trs[1].children[0].appendChild(svg2);
			}

		}
	};
	xhr.send();	
}
window.onload = loadsvg;
</script>
</body>
</html>