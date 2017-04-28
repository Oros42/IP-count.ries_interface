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
		#tables{
			display: flex;
			flex-flow: wrap;
		}
		table{
			font-size: 16px;
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			line-height: 1.5;
			border-collapse: collapse;
			margin-left: 20px;
			margin-bottom: 20px;
		}
		th {
			background-color: #4BAE4F;
			color: white;
			padding: 8px;
			border: 1px solid #ddd;
		}	
		tr:nth-child(even) {
			background-color: #f2f2f2
		}
		td{
			border: 1px solid #ddd;
			text-align: left;
			padding: 8px;
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
<div id="tables">
<?php
foreach ($countries_files as $countries_file => $title) {
?>
	<table id="<?php echo htmlentities($title); ?>">
	<tr>
		<th colspan='4'><?php echo htmlentities($title); ?></th>
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
		while(! feof($file)) {
			$c=fgetcsv($file);
			if($c[0]!=""){
				$cpt++;
				echo "	<tr><td>".$cpt."</td><td>".$c[1]."</td><td>".$c[2]."</td><td>".$c[0]."</td></tr>\n";
			}
		}
		fclose($file);
	}else{
		echo "	<tr><td colspan='4'>Error : can't open ".htmlentities($countries_file)."</td><tr>\n";
	}
	?>
	</table>
<?php
}
?>
</div>
</body>
</html>