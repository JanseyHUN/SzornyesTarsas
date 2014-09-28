<?php
/*a két változó kezdő értéket kap, mert lehet, hogy nem érkezik be használható adat, így legyen kezdőértékük!*/   
$dicenum = 0;
$diceshape = 0;


/*Az isset fgv-t használni kötelező, amennyiben POST globális változóban várunk adatot!
 *Ez a fgv nem száll el, ha vizsgálat alá vonom az általam feltételezett adatok létezését,
 *tehát alább az a feltétel tartalma, hogy a POST globális környezeti változóban van-e
 *egyáltalán szint és bonusz kulcsnevű változó
 */
if (isset($_POST["szint"])){
  $dicenum   = $_POST["szint"];  
}
if (isset($_POST["bonusz"])){
  $diceshape = $_POST["bonusz"];  
}

/*Már az elejére ki lehet csempészni a kocka oldalainak levizsgálását. Ha nincs 2, akkor 
 * elküldjük a k....-ba :) */
if ($diceshape < 2){
  print "Válassz legalább 2 oldalú 'kockát'";
  exit;
}
  /*Itt volt egy rész, ahol a változódi értéke megváltozik és ezért generáltál még 1-2 plusz belső változót.
   * Ilyenkor sokkal egyszerűbb fgv-t csinálni, aminek a belsejében akármerre válzohat a változód, az visszatér
   * valamivel, most éppen az összeszámolt aranyakkal és a számodta lényeges változók megmaradnak annak,
   * ami neked kell. Igy született meg a Szamolas fgv.*/
    print "Kockák száma(A szörny szintje): ".$dicenum."<br/>";
    print "Bónusz kockák oldalainak száma: ".$diceshape." (pirossal az extra dobás)<br/>";
    $gold = Szamolas($dicenum, $diceshape);
    /* itt a végleges arany kerül kiszámításra ,levonva az eredeti kockák
     * számát mivel az 1-es dobás nem számít.A bónusz dobások ezt az érté-
     * ket nem módosítják.Majd ebből kivonásra kerül gy szintmódosító ami
     * kissebb szintű lényeket sújtja.
     */
    $mod_gold = $gold - $dicenum + (5 - (10 - $dicenum) );
    if ($mod_gold < 0) {
       $mod_gold = 0;
    }
        /* Próbáld azt a sorrendet követni, hogy ELNEVEZÉS - ADAT. 
     * Tehát Számolt arany: 12
     *       Módosító     : 3 
     *       stb...
     * */
    
    print "<br/>Számolt arany: ".$gold." - ".$dicenum." mivel az egyes dobás nem ér<br/> ";
    print "Szörnyszint módosító, kicsiket sújtja: ".(5 - (10 - $dicenum))."<br/>";
    print "Kapott arany: ".$mod_gold;
    
    
    function Szamolas($num,$diceshape){
      while ($num > 0){
        $x = mt_rand(1,$diceshape);
        if ($x == $diceshape){   //bónuszdobás
          $num++;
          $szoveg .= "<font color='red'>".$x.","."</font>";
        }else {
          $szoveg .= "<font color='black'>".$x.","."</font>";
        }
        
        $gold = $gold + $x; //összeadja az értékeket
        $num--;
      }
      /*Észrevettem, hogy az utolsó ,-vel nem tudtál mit kezdeni. Számos alkalom lesz még, amikor a vessző
       * elméletileg gondot okozhat, ezért elárulom a tutit. Nyugodtan nyomjad a vesszőket, majd ha a megkreált
       * szöveg összeállt, akkor a végét vágd le és egészítsd ki, ahogy az valóban jó.
       * Erre jó a substr fgv, ez tulajdonképpen másol -tól-ig*/
      $szoveg = substr($szoveg,0,strlen($szoveg) - 8)."</font>";
      print $szoveg;
      return $gold;
    }    
?>
