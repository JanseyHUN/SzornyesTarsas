<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<head>
<style type="text/css">
#zsakfel {
	background-image: url(zsakfel.jpg);
}
#0100 {
	background-image: url(zsakbalra.jpg);
}
#0010 {
	background-image: url(zsakle.jpg);
}
#0001 {
	background-image: url(zsakjobbra.jpg);
}
#1100 {
	background-image: url(lejobbra.jpg);
}
#0110 {
	background-image: url(feljobbra.jpg);
}
#0011 {
	background-image: url(jobbrale.jpg);
}
#1001 {
	background-image: url(jobbrafel.jpg);
}
#1110 {
	background-image: url(tjobbra.jpg);
}
#0111 {
	background-image: url(tle.jpg);
}

#ures {
	background-color: #0A3;
}

</style>
</head>

<body>

<table border=1 >

<?php						/*Változók inicializálása*/
$sor=20;
$oszlop=1;
$terkep[20][1]=0;

for ($a=1; $a<=50; $a++) {		/*Teljes tábla feltöltése nullákkal*/
	for ($b=1; $b<=50; $b++) {
	$terkep[$a][$b]="0";
	}
}

$terkep[20][1]=1;

for ($lepes=1; $lepes<200; $lepes++) { /*Első útvonal*/
	if ($oszlop==50) {
		break;
	}
	
	switch ($sor) {
		case 1:
		case 2:
		case 3:
		case 4:
			$irany=rand(2,3);
			break;
		case 37:
		case 38:
		case 39:
		case 40:
			$irany=rand(1,2);
			break;
		default:
			$irany=rand(1,4);
								}
	
	switch ($irany) {
		case 1:
			$sor--;
			$terkep[$sor][$oszlop]=1;
			$sor--;
			$terkep[$sor][$oszlop]=1;
			break;
		case 2:
		case 4:
			$oszlop++;
			$terkep[$sor][$oszlop]=1;
			break;
		case 3:
			$sor++;
			$terkep[$sor][$oszlop]=1;
			break;
								}
		
		
	
}

$sor=20;
$oszlop=1;
for ($lepes=1; $lepes<200; $lepes++) {	/*Második útvonal*/
	if ($oszlop==50) {
		break;
	}
	
	switch ($sor) {
		case 1:
		case 2:
		case 3:
		case 4:
			$irany=rand(2,3);
			break;
		case 37:
		case 38:
		case 39:
		case 40:
			$irany=rand(1,2);
			break;
		default:
			$irany=rand(1,4);
								}
	
	switch ($irany) {
		case 1:
			$sor--;
			$terkep[$sor][$oszlop]=2;
			break;
		case 2:
		case 4:
			$oszlop++;
			$terkep[$sor][$oszlop]=2;
			break;
		case 3:
			$sor++;
			$terkep[$sor][$oszlop]=2;
			$sor++;
			$terkep[$sor][$oszlop]=2;
			break;
								}
		
		
	
}

$sor=20;
$oszlop=1;
for ($lepes=1; $lepes<200; $lepes++) {	/*Harmadik útvonal*/
	if ($oszlop==50) {
		break;
	}
	
	switch ($sor) {
		case 1:
		case 2:
		case 3:
		case 4:
			$irany=rand(2,3);
			break;
		case 37:
		case 38:
		case 39:
		case 40:
			$irany=rand(1,2);
			break;
		default:
			$irany=rand(1,4);
								}
	
	switch ($irany) {
		case 1:
			$sor--;
			$terkep[$sor][$oszlop]=3;
			break;
		case 2:
		case 4:
			$oszlop++;
			$terkep[$sor][$oszlop]=3;
			break;
		case 3:
			$sor++;
			$terkep[$sor][$oszlop]=3;
			break;
								}
		
		
	
}

$sor=20;
$oszlop=1;
for ($lepes=1; $lepes<200; $lepes++) { 	/*Negyedik útvonal*/
	if ($oszlop==50) {
		break;
	}
	
	switch ($sor) {
		case 1:
		case 2:
		case 3:
		case 4:
			$irany=rand(2,3);
			break;
		case 37:
		case 38:
		case 39:
		case 40:
			$irany=rand(1,2);
			break;
		default:
			$irany=rand(1,4);
								}
	
	switch ($irany) {
		case 1:
			$sor--;
			$terkep[$sor][$oszlop]=4;
			break;
		case 2:
		case 4:
		
		
			$oszlop++;
			$terkep[$sor][$oszlop]=4;
			break;
		case 3:
			$sor++;
			$terkep[$sor][$oszlop]=4;
			break;
								}
	
}

for ($sor=2; $sor<39; $sor++){
	for ($oszlop=2; $oszlop<49; $oszlop++) {
		$textura="";
		if ($terkep[$sor][$oszlop]>0)  {
			if ($terkep[$sor-1][$oszlop]!==0) {
			$textura=$textura.'o';
			}
			else {$textura=$textura.'x';}
			
			if ($terkep[$sor][$oszlop+1]!==0) {
			$textura=$textura.'o';
			}
			else {$textura=$textura.'x';}
			
			if ($terkep[$sor+1][$oszlop]!==0) {
			$textura=$textura.'o';
			}
			else {$textura=$textura.'x';}
			
			if ($terkep[$sor][$oszlop-1]!==0) {
			$textura=$textura.'o';
			}
			else {$textura=$textura.'x';}
			}
			$terkep[$sor][$oszlop]=$textura;
		}
			
}


for ($tablasor=1; $tablasor<41; $tablasor++) {		
	echo '<tr>';
	for ($tablaoszlop=1; $tablaoszlop<51; $tablaoszlop++) {
	$ertek=$terkep[$tablasor][$tablaoszlop];
	echo '<td ';
	switch ($ertek) {
		case 'xxxo':
			echo 'id="zsakfel"><img src="xxxo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'xxox':
			echo 'id="0100"><img src="xxox.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'xxoo':
			echo 'id="0010"><img src="xxoo.jpg">'.$tablasor.'x'.$tablaoszlop.'<td>';
			break;
		case 'xoxx':
			echo 'id="0001"><img src="xoxx.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'xoxo':
			echo 'id="1100"><img src="xoxo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'xoox':
			echo 'id="0110"><img src="xoox.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'xooo':
			echo 'id="0011"><img src="xooo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'oxxx':
			echo 'id="1001"><img src="oxxx.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'oxxo':
			echo 'id="1111"><img src="oxxo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'oxox':
			echo 'id="1001"><img src="oxox.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'oxoo':
			echo 'id="0110"><img src="oxoo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'ooxx':
			echo 'id="0110"><img src="ooxx.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'ooxo':
			echo 'id="0110"><img src="ooxo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'ooox':
			echo 'id="0110"><img src="ooox.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		case 'oooo':
			echo 'id="0110"><img src="oooo.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
			break;
		default:
			echo 'id="ures"><img src="grass.jpg">'.$tablasor.'x'.$tablaoszlop.'</td>';
								}
				
												}
	echo '</tr>';
}
?>

</table>

</body>