<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  <title>Untitled Document</title>
    <style type="text/css">      #zsakfel {background-image: url(zsakfel.jpg);}
      #0100 {background-image: url(zsakbalra.jpg);}      #0010 {background-image: url(zsakle.jpg);}
      #0001 {background-image: url(zsakjobbra.jpg);}      #1100 {background-image: url(lejobbra.jpg);}      #0110 {background-image: url(feljobbra.jpg);}
      #0011 {background-image: url(jobbrale.jpg);}      #1001 {background-image: url(jobbrafel.jpg);}      #1110 {background-image: url(tjobbra.jpg);}
      #0111 {background-image: url(tle.jpg);}
      #ures {background-color: #0A3;}    </style>  </head><body><table border=0 cellspacing="0" cellpadding="0" style="border: none;" >

<?php						/*A db.php kell az adatbázis műveletekhez!!!*/require_once 'db.php'; $terkep1 = array();
 $terkep2 = array();

for ($a = 0; $a < 40; $a++) {
  for ($b = 0; $b < 50; $b++) {
    $terkep1[$a][$b] = 0;
    $terkep2[$a][$b] = "";
  }
}
/*Az alapozás függvénybe raktam bele azokat a műveleteket, amelyeket te csináltál meg. * Ilyen formában összeszedettebben néz ki.*/
for ($i = 0; $i < 5; $i++) {              /*Ez adja meg az útvonalak számát*/
  alapozas($terkep1,$terkep2);
}/*Amikor készen van a terkep2-ben eltárolt térkép, ahol már mintaszerűen van tárolva, * akkor csak a kirajzolo függvénynek kell átadni. Ez a rész is elkülönítésre került, * mert ennek a résznek semmi másra nem kell figyelnie, csak és kizárólag a megjelenítésre!!!*/



TerkepKirajzolasa($terkep2);/*Alább helyezkednek el az adatábisba elmentésre és onnan való * kiolvasásra hivatott függvények. Direkt nem osztály formájúan és objektumorientáltan * csináltam meg, hogy érthetőbb legyen .:))) *  * A kiolvasáshoz nem kell más, csak a térkép egyedi azonosítóját tudni. Jelenleg egy elmentett * térkép van az adatbázisban. Ennek az ID-je 2.  *  * Kiolvasáshoz a EgyTerkepKiolvasasaAdatbazisbolEsKirajzolasa() függvény kell. Ez egy olyan tömböt ad vissza, * amit egyből bele lehet dobni a TerkepKirajzolasa() függvénybe és az ki fogja rajzolni a weboldalra. *  * Elmenteni pedig a TerkepAdatbazisba() függvénnyel lehet. Itt ugyanazt a textúra mintás tömböt kell átadni a függvénynek, * mint amilyet kiolvasásnál kapunk meg *  * példa kiolvasásra és megjelenítsére: *  *   $terkep = EgyTerkepKiolvasasaAdatbazisbolEsKirajzolasa(2); *   TerkepKirajzolasa($terkep); *  * példa elmentésre: * *   TerkepAdatbazisba($terkep); *    * Megfigyelheted, hogy én 2 terkep tömbbel dolgoztam. Az egyik csak 0-kat és 1-eseket tartalmaz a jobb áttekinthetőség kedvéért * míg a másik a mintákat. Ezért van terkep1 és terkep2 tömb. * */function EgyTerkepKiolvasasaAdatbazisbolEsKirajzolasa($id){  $terkepStr = "";  $terkep2 = array();
  $terkepStr = TerkepAdatbazisbol($id);
  $terkep2 = convertTerkepStr2TerkepArray($terkepStr);
  TerkepKirajzolasa($terkep2);}function convertTerkepStr2TerkepArray($terkepStr){    $pozicio = strpos($terkepStr, "|");
  $tmpArr = explode("|", $terkepStr);
  
  $i = 0;
  foreach ($tmpArr as $subtmpArr) {
    $tmpArr2 = explode(",", $subtmpArr);
  
    for ($j = 0; $j < count($tmpArr2); $j++) {
      $terkep2[$i][$j] = $tmpArr2[$j];
    }
    $i++;
  }  return $terkep2;}function TerkepAdatbazisbol($id){  $db = new AdatbazisKezelo();
  $db->SetAdminEmail('csapo.janos@gmail.com');
  $db->InitDB(	/*hostname*/"127.0.0.1",
      /*username*/"fejesattila",
      /*password*/"monor123",
      /*database name*/"fejesattila",
      /*table name*/"terkepek");
  $db->SetDataTable("terkepek");
  
  $terkep = $db->TerkepBeolvasasa($id);  return $terkep;}function TerkepAdatbazisba($terkep){  $terkepString = TerkepFelbontasa($terkep);    $db = new AdatbazisKezelo();  $db->SetAdminEmail('csapo.janos@gmail.com');
  $db->InitDB(	/*hostname*/"127.0.0.1",
      /*username*/"fejesattila",
      /*password*/"monor123",
      /*database name*/"fejesattila",
      /*table name*/"terkepek");
  $db->SetDataTable("terkepek");    $res = $db->TerkepMentese($terkepString);  if (!$res) { echo $db->GetErrorMessage();}  }function TerkepFelbontasa($terkep){  $tmpArr = array();
  foreach ($terkep as $elsoBontas) {
    $tmpArr[] = implode(',',$elsoBontas);
  }
  $result = implode('|',$tmpArr);
  return $result;}function alapozas(&$terkep1,&$terkep2){    /*Az első útvonal kezdetét adom meg*/
  $sor = rand(0,39);  $oszlop=0;
  /* $sor = 20; fix induló érték később törlendő!!!!!*/
  /*szükség van feljegyezni az út kezdetét, mert később a
   * textura konvertalasnal kelleni fog!*/
  $kezdosor = $sor;
  $terkep1[$sor][$oszlop]=1;

  
  /*Első útvonal*/
  for ($lepes=0; $lepes<300; $lepes++) {
    if ($oszlop == 50) {
      break;
    }
	
	$irany=rand(1,4);
	
    if ($lepes == 0) {   /*Az első oszlopból mindig jobbra indul*/
	  $irany=4;
    }
  
    switch ($irany) {
      /*fel*/
      case 1:
        if ($sor != 0) {
          $sor--;
        }
        $terkep1[$sor][$oszlop]=1;
        break;
        /*balra*/
      case 2: 
	    break;
      /*le*/
      case 3:
        if ($sor != 39){
          $sor++;
        }
        $terkep1[$sor][$oszlop]=1;
        break;
        /*jobbra*/
      case 4:
        if ($oszlop != 49){
          $oszlop++;
        }
        $terkep1[$sor][$oszlop]=1;
        	
        /*Ha az út elérte a jobb oldalt, akkor hozzáadunk egyet         *  és elérjük az oszlop == 50 feltételt
        * a for ciklus elején*/
        if($oszlop == 49){
          $oszlop++;          $terkep1[$sor][$oszlop]=1;
        }
        break;
      /*default:break;*/
    }
  }
  
  for ($sor_ = 0; $sor_ < 40; $sor_++){
    for ($oszlop_ = 0; $oszlop_ < 50; $oszlop_++) {
  
      $textura = "";
      if ($terkep1[$sor_][$oszlop_] == 1)  {
  
        /*Az első elem mindig zöld lesz, ha nem vizsgáljuk!
         * Még kiíratás előtt meg kell vizsgálni a környező
        * elemeit, hátha módosítani kell rajta!!!*/
        if( ($oszlop_ == 0) && ($kezdosor == $sor_) ){
          if ($terkep1[$sor_][$oszlop_] == 1) {
            $textura = 'ooox';
          }
          $terkep2[$sor_][$oszlop_] = $textura;
          continue;
        }
          /*felette lévő cella vizsgálat*/
        if($sor_ > 0){
          if ($terkep1[$sor_-1][$oszlop_] === 1) { $textura .= 'o'; }
            else {$textura .= 'x';
          }
        }else {$textura .= 'x';}
        	        /*jobb oldal vizsgálata*/
        if($oszlop_ < 50){
          if ($terkep1[$sor_][$oszlop_+1] === 1) { $textura .= 'o'; }
          else {$textura .= 'x';}
        }
        	        /*alatta lévő cella vizsgálata*/
        if ($sor_ < 40){
          if ($terkep1[$sor_+1][$oszlop_] === 1) { $textura .= 'o'; }
            else { $textura .= 'x';}
        }
        	        /*bal oldali cella vizsgálata*/
        if($oszlop_ > 0){
          if ($terkep1[$sor_][$oszlop_-1] === 1) { $textura .= 'o'; }
            else { $textura .= 'x';}
        }else { $textura .= 'x';}
      }
  
      $terkep2[$sor_][$oszlop_] = $textura;            /*oooo - mindenhol nyitott
       *xooo - balrol zart
      *oxoo - jobbrol zart
      *ooxo - alulrol zart
      *ooox - balrol zart
      * */
    }
  }}function TerkepKirajzolasa(&$terkep2){    for ($tablasor = 0; $tablasor < 40; $tablasor++) {
    echo '<tr>';
    for ($tablaoszlop = 0; $tablaoszlop < 50; $tablaoszlop++) {
      $minta = $terkep2[$tablasor][$tablaoszlop];
       
      echo '<td ';
  
      switch ($minta) {
        case 'xxxo':
          echo 'id="zsakfel"><img src="./old/zend/map/xxxo.jpg">fffffffffffffffffffffff</td>';break;
        case 'xxox':
          echo 'id="0100"><img src="./old/zend/map/xxox.jpg"></td>';break;
        case 'xxoo':
          echo 'id="0010"><img src="./old/zend/map/xxoo.jpg"></td>';break;
        case 'xoxx':
          echo 'id="0001"><img src="./old/zend/map/xoxx.jpg"></td>';break;
        case 'xoxo':
          echo 'id="1100"><img src="./old/zend/map/xoxo.jpg"></td>';break;
        case 'xoox':
          echo 'id="0110"><img src="./old/zend/map/xoox.jpg"></td>';break;
        case 'xooo':
          echo 'id="0011"><img src="./old/zend/map/xooo.jpg"></td>';break;
        case 'oxxx':
          echo 'id="1001"><img src="./old/zend/map/oxxx.jpg"></td>';break;
        case 'oxxo':
          echo 'id="1111"><img src="./old/zend/map/oxxo.jpg"></td>';break;
        case 'oxox':
          echo 'id="1001"><img src="./old/zend/map/oxox.jpg"></td>';break;
        case 'oxoo':
          echo 'id="0110"><img src="./old/zend/map/oxoo.jpg"></td>';break;
        case 'ooxx':
          echo 'id="0110"><img src="./old/zend/map/ooxx.jpg"></td>';break;
        case 'ooxo':
          echo 'id="0110"><img src="./old/zend/map/ooxo.jpg"></td>';break;
        case 'ooox':
          echo 'id="0110"><img src="./old/zend/map/ooox.jpg"></td>';break;
        case 'oooo':
          echo 'id="0110"><img src="./old/zend/map/oooo.jpg"></td>';break;
        default:
          echo 'id="ures"><img src="./old/zend/map/grass.jpg"></td>';break;
      }
    }
    echo '</tr>';
  } }
?>
</table>
</body></html>