<?php

require_once 'szornyek.php';
require_once 'harcrendszer.php';
require_once 'jatekos.php';
require_once 'db.php';

try {
  $db = new AdatbazisKezelo();
  $db->KapcsolatKeszites();
  $db->SetDataTable("jatekosok");
  $adatok["oszlop"] = 0;
  $adatok["sor"] = 0;
  $adatok["jatekosid"] = 1;
  $db->getJatekosPozicio(&$adatok);
  
  echo "<br/>Jatékosid: ".$adatok["jatekosid"]."<br/>";
  echo "Sor: ".$adatok["sor"]."<br/>";
  echo "Oszlop: ".$adatok["oszlop"]."<br/>";
  
} catch (Exception $e) {
  echo $e->getMessage();
}





$jatekos = new jatekos("Jansey");
$jatekos->Kiiratas();



$szornyek = new Szornyek();
$szornyek->SzornyGenerator(3);
$szornyekSor = $szornyek->getSzornyek();
$szornyek->SzornyekKiiratas();

$kuzdelem = new harcrendszer();
$kuzdelem->setJatekos($jatekos);
$kuzdelem->setSzornyek($szornyekSor);
$kuzdelem->TamadasOsszes();



?>