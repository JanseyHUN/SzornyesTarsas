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

function JatekosLista(){
	$jatekid = 0;
	if(isset($_SESSION['id_of_game'])){
		$jatekid = $_SESSION['id_of_game'];
	}

	if ($jatekid != 0) {
		$Jatek = new aJatek();
		$GameParams = $Jatek->getJatekosokEsJatekNev($jatekid);

		echo "<div style='margin-bottom: 2em; background-color: #D5C4CA;'>";
		echo "JátékID: ".$jatekid."</div>";
		
		echo "<div style='margin-bottom: 2em; padding-bottom: 0.6em; padding-top: 0.6em; background-color: #D5C4CA;'>";
		echo "Játék Neve:<br/>".$GameParams['jatekneve']."</div>";
		      
		echo "<div style='height: 15.5em; background-color: #D5C4CA;'>";
		echo "A játék tulajdonságai:";
		
		echo "<p id='player1'>".$GameParams['jatekos1']."(".$GameParams['userid1'].")</p>";
		echo "<p id='player2'>".$GameParams['jatekos2']."(".$GameParams['userid2'].")</p>";
		echo "<p id='player3'>".$GameParams['jatekos3']."(".$GameParams['userid3'].")</p>";
		echo "<p id='player4'>".$GameParams['jatekos4']."(".$GameParams['userid4'].")</p>";
		echo "</div>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Szörnyes Társas</title>
      
      <script type="text/javascript">

      
		
      function AdatKuldes(){
    	  var karakterId = getID();
    	  var JatekNev = document.getElementById("gamename").value;
    	    
    	  var kuldesDiv = document.getElementById("generateFormDiv").innerHTML = '<form id="generateForm" method="post" action="generateGame.php"><input id="karakter" name="karakter" type="hidden" value="'+karakterId+'"><input id="jatekneve" name="jatekneve" type="hidden" value="'+JatekNeve+'"></form>';
          kuldes = document.getElementById("generateForm");
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
    <?php JatekosLista();?>
  </div>
  
  
  <div style="width: 10em; height: 2em; float: left; margin-left: 2em; height: auto; background-color: #9D919E; padding: 0em; padding-top: 0.6em; text-align: center;">
    
    <div style="background-color: #D5C4CA; margin-bottom: 1em;">
      <a href='startGame.php'>START</a>
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










