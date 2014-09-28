<?php
header("Content-Type: text/html; charset=UTF-8");
require_once 'db.php';
require_once 'targy.php';
require_once 'include/konst.php';

$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetDataTable("targyak");


/*ar, sebzesmin,
 *sebzesmax, megnevezes,
*hely, kategoria,
*hatashelye, modosito,
*szint, toltet*/

$targy[0] = 10;
$targy[1] = 1;
$targy[2] = 2;
$targy[3] = "szopó álarc";
$targy[4] = konst::helyFEJ;
$targy[5] = konst::katPLUSZOS;
$targy[6] = konst::hatMOZGAS;
$targy[7] = 1;
$targy[8] = 1;
$targy[9] = 1;

if ( !$db->TargyakMentese($targy) ){
  echo $db->GetErrorMessage();
}else {
  echo "Tárgy mentése sikeres";
}



?>