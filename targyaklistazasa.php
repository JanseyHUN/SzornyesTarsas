<?php
header("Content-Type: text/html; charset=UTF-8");
require_once 'db.php';
require_once 'include/konst.php';

$db = new AdatbazisKezelo();
$db->KapcsolatKeszites();
$db->SetDataTable("targyak");

$targylista = $db->TargyakListazasa();

/*ar, sebzesmin,
 *sebzesmax, megnevezes,
*hely, kategoria,
*hatashelye, modosito,
*szint, toltet*/

foreach ($targylista as $targy) {
  
  echo "Tárgy ID: ".$targy["id"]."<br/>";
  echo "Megnevezése: ".$targy["megnevezes"]."<br/>";
  echo "Ára: ".$targy["ar"]." arany<br/>";
  echo "Sebzése: ".$targy["sebzesmin"]."-".$targy["sebzesmax"]."<br/>";
  echo "A tárgy elhelyezkedése: ".konst::convHely($targy["hely"])."<br/>";
  echo "Kategóriája: ".konst::convKategoria($targy["kategoria"])."<br/>";
  echo "Erre gyakorol hatást: ".konst::convHatas($targy["hatashelye"])."<br/>";
  echo "Módósító: ".$targy["modosito"]."<br/>";
  echo "A Tárgy szintje: ".$targy["szint"]."<br/>";
  echo "Ennyi töltet van a tárgyban(ennyiszer lehet használni): ".$targy["toltet"]."<br/>";
  
  echo "<br/>";
}


?>
