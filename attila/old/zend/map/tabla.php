<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<head>
<style type="text/css">
#ures {
	background-color: #0F3;
}
#teli {
	background-color: #0A3;
}
</style>
</head>

<body>

<table border=1 >


<?php


for ($sor=1; $sor<40; $sor++) {
	echo '<tr>';
	for ($oszlop=1; $oszlop<50; $oszlop++) {
	$ertek=rand(1,2);
	echo '<td ';
	switch ($ertek) {
		case 1:
			echo 'id="ures">*'.$sor.'x'.$oszlop.'*</td>';
			break;
		case 2:
		
		
			echo 'id="teli">*'.$sor.'x'.$oszlop.'*</td>';
			
			
			break;
					}
					
					
					
		
												}
	echo '</tr>';
}

?>
</table>