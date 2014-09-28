<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once './map/map_fixedbyJani.php';
require_once 'db.php';
require_once './include/aJatek.php';
ob_end_clean();
$altalanos = new altalanos();
$db = new AdatbazisKezelo();
$Jatek = new aJatek();
$db->KapcsolatKeszites();
$db->SetDataTable("felhasznalok");

/*$fgkeretrendszer->SetRandomKey('qSRcVS6DrTzrPvr');*/

if(!altalanos::CheckLogin())
{
    altalanos::RedirectToURL("login.php");
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Szörnyes Társas</title>
      
      <script type="text/javascript">

      function getID(){
          var karakter = document.getElementById("player1").innerHTML;
          var JatekNev = document.getElementById("gamename").value;
          
          if (karakter == "1. Játékos") {
				alert("Nincs választott játékos!");
				exit;
			}

    		if (JatekNev == "") {
    			alert("Nincs kitöltve a játék neve!");
				exit;
			}

          var id = karakter;

          if (karakter == "Arthur Király") {
				id = "1";
			}
			if (karakter == "Íjász Szamuráj") {
				id = "2";
			}
			if (karakter == "Tűzes Íjász") {
				id = "3";
			}
			if (karakter == "Alázatos Szamuráj") {
				id = "4";
			}
			if (karakter == "Egyenes Lándzsa") {
				id = "5";
			}
			if (karakter == "Harcos") {
				id = "6";
			}
			if (karakter == "Lovag") {
				id = "7";
			}
			if (karakter == "Női Bárdos") {
				id = "8";
			}
			if (karakter == "Lángpallos Bestia") {
				id = "9";
			}
			if (karakter == "Sisak Rejtély") {
				id = "10";
			}
			if (karakter == "Hitman") {
				id = "11";
			}
			if (karakter == "Tolkien") {
				id = "12";
			}
			
			return id;
      }

      function AdatKuldes(){
    	  var karakterId = getID();
    	  var JatekNev = document.getElementById("gamename").value;
    	    
    	  var kuldesDiv = document.getElementById("startFormDiv").innerHTML = '<form id="startForm" method="post" action="startGame.php"><input id="karakter" name="karakter" type="hidden" value="'+karakterId+'"><input id="jatekneve" name="jatekneve" type="hidden" value="'+JatekNeve+'"></form>';
          kuldes = document.getElementById("startForm");
          if (kuldes) {                
				kuldes.submit();
			}
     }
      
      
        function KepenKattintas(id){
            
            if ( id == 'pic001') {
            	document.getElementById("player1").innerHTML = "Arthur Király";
			} 
			if (id == "pic002") {
				document.getElementById("player1").innerHTML = "Íjász Szamuráj";
			}
			if (id == "pic003"){
				document.getElementById("player1").innerHTML = "Tűzes Íjász";
			}
			if (id == "pic004"){
				document.getElementById("player1").innerHTML = "Alázatos Szamuráj";
			}
			if (id == "pic005"){
				document.getElementById("player1").innerHTML = "Egyenes Lándzsa";
			}
			if (id == "pic006"){
				document.getElementById("player1").innerHTML = "Harcos";
			}
			if (id == "pic007"){
				document.getElementById("player1").innerHTML = "Lovag";
			}
			if (id == "pic008"){
				document.getElementById("player1").innerHTML = "Női Bárdos";
			}
			if (id == "pic009"){
				document.getElementById("player1").innerHTML = "Lángpallos Bestia";
			}
			if (id == "pic010"){
				document.getElementById("player1").innerHTML = "Sisak Rejtély";
			}
			if (id == "pic011"){
				document.getElementById("player1").innerHTML = "Hitman";
			}
			if (id == "pic012"){
				document.getElementById("player1").innerHTML = "Tolkien";
			}
        }                 
      </script>
      
</head>
<body>

<div style='margin-left: auto; margin-right: auto; width: 65em; padding-left: 1em; padding-bottom: 3em; padding-top: 2em; background-color: #e5dbcf;'>

  <fieldset style='height: 20em; width: 15em; background-color: #F2EDDE; float: left;'>
    <legend>FőMenü</legend>
      <div>
        Bejelentkezve:<br/><?= altalanos::UserFullName(); ?>
        
        <p><a href='change-pwd.php'>Jelszó váltás</a></p>
        <p><a href='createGame.php'>Játék indítása</a></p>
        <p><a href='joinGame.php'>Kapcsolódás Játékhoz</a></p>        
        <p><a href='usersGame.php'>Felhasználók/KÉSŐBB/</a></p>
        
        <br/><br/><br/>
        <p><a href='logout.php'>Kijelentkezés</a></p>
      </div>
  </fieldset>
  
  <fieldset style='margin-left: 4em; height: 35em; width: 45em; background-color: #F2EDDE;'>
  <legend>Tartalom</legend>
    <fieldset style="margin-bottom: 1em; text-align: center;">
      A játék elkészült, a FRISSTÉS gombbal nézheted meg, hogy csatlakoztak-e már!
    </fieldset>
  
  <div style="overflow: auto; height: 25em; width: 11em; background-color: gray; float: left; margin: auto;">
    <table>
	  <tr><td><img id="pic001" onclick="KepenKattintas('pic001')" src="./pictures/avatars/001.png" style="width: 70px; height: 70px"/></td><td><img id="pic002" onclick="KepenKattintas('pic002')" src="./pictures/avatars/002.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img id="pic003" onclick="KepenKattintas('pic003')" src="./pictures/avatars/003.png" style="width: 70px; height: 70px"/></td><td><img id="pic004" onclick="KepenKattintas('pic004')" src="./pictures/avatars/004.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img id="pic005" onclick="KepenKattintas('pic005')" src="./pictures/avatars/005.png" style="width: 70px; height: 70px"/></td><td><img id="pic006" onclick="KepenKattintas('pic006')" src="./pictures/avatars/006.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img id="pic007" onclick="KepenKattintas('pic007')" src="./pictures/avatars/007.png" style="width: 70px; height: 70px"/></td><td><img id="pic008" onclick="KepenKattintas('pic008')" src="./pictures/avatars/008.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img id="pic009" onclick="KepenKattintas('pic009')" src="./pictures/avatars/009.png" style="width: 70px; height: 70px"/></td><td><img id="pic010" onclick="KepenKattintas('pic010')" src="./pictures/avatars/010.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img id="pic011" onclick="KepenKattintas('pic011')" src="./pictures/avatars/011.png" style="width: 70px; height: 70px"/></td><td><img id="pic012" onclick="KepenKattintas('pic012')" src="./pictures/avatars/012.png" style="width: 70px; height: 70px"/></td></tr>
	</table>
  </div>
  
  <div style="width: 15em; height: 2em;  float: left; margin-left: 2em; padding-top: 0.6em; background-color: #9D919E;">
    <div style="margin-bottom: 2em; background-color: #D5C4CA;">
      JátékID:<?php $terkepID = $Jatek->JatekGeneralasEsMentes(); echo " ".$_SESSION["id_of_game"];?>
    </div>
    
    <div style="margin-bottom: 2em; padding-bottom: 0.6em; padding-top: 0.6em; background-color: #D5C4CA;">
      Játék Neve:<br/>
      <?php echo $Jatek->getJatekNeve($terkepID);?>
    </div>
    
    <div style="height: 15.5em; background-color: #D5C4CA;">
      A játék tulajdonságai:
      
      <p><b id="player1"><?php echo $Jatek->JatekosKarakterMentesEsKiiratas($terkepID);?></b></p>
      
      <p id="player2">
        <?php 
          $jatekos_res = $Jatek->getJatekos(2,$_SESSION["id_of_game"]);
          if ($jatekos_res != null) {
            echo $jatekos_res[0]."(".$jatekos_res[1].")";
          }
        ?>
      </p>
      <p id="player3">
        <?php 
          $jatekos_res = $Jatek->getJatekos(3,$_SESSION["id_of_game"]);
          if ($jatekos_res != null) {
            echo $jatekos_res[0]."(".$jatekos_res[1].")";
          }
        ?>
      </p>
      <p id="player4">
        <?php 
          $jatekos_res = $Jatek->getJatekos(4,$_SESSION["id_of_game"]);
          if ($jatekos_res != null) {
            echo $jatekos_res[0]."(".$jatekos_res[1].")";
          }
        ?>
      </p>
    </div>
  </div>
  
  
  <div style="width: 10em; height: 2em; float: left; margin-left: 2em; height: auto; background-color: #9D919E; padding: 0em; padding-top: 0.6em; text-align: center;">
    
    <div style="background-color: #D5C4CA; margin-bottom: 1em;">
      <a href='playGame.php'>START</a>
    </div>
    
    <div style="background-color: #D5C4CA;">
      <a href='refreshGame.php'>FRISSÍT</a>
    </div>
  </div>
  
  </fieldset>
  
  

  <div>
	<p style="font-size: x-small;">Szörnyes Társas &copy; 2013</p>
  </div>
  
</div>  
  


</body>
</html>
