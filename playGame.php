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
    	    
    	  var kuldesDiv = document.getElementById("generateFormDiv").innerHTML = '<form id="generateForm" method="post" action="generateGame.php"><input id="karakter" name="karakter" type="hidden" value="'+karakterId+'"><input id="jatekneve" name="jatekneve" type="hidden" value="'+JatekNeve+'"></form>';
          kuldes = document.getElementById("generateForm");
          if (kuldes) {                
				kuldes.submit();
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
      A játék elindult! 
    </fieldset>
  
  <div style="overflow-x: scroll; overflow-y: scroll; height: 25em; width: 44em; background-color: gray; float: left; margin: auto;">
	  <?php
	  echo "<table cellspacing='2' cellpadding='0' border='0' >";
	    $terkep_id = $_SESSION['id_of_map'];
	  	EgyTerkepKiolvasasaAdatbazisbolEsKirajzolasa($terkep_id);
	  	$jatekid = $_SESSION["id_of_game"];
	  	$Jatek->JatekStartFlag(true,$jatekid);
	  echo '</table>';
	  ?>
  </div>
  </fieldset>
  
  

  <div>
	<p style="font-size: x-small;">Szörnyes Társas &copy; 2013</p>
  </div>
  
</div>  
  


</body>
</html>
