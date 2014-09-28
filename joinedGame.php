<?PHP
header("Content-Type: text/html; charset=UTF-8");
ob_start();
require_once("./include/altalanos.php");
require_once 'db.php';
require_once './include/aJatek.php';
ob_end_clean();
$altalanos = new altalanos();
$db = new AdatbazisKezelo();
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
	if(isset($_POST['jatekid'])){
		$jatekid = $_POST['jatekid'];
	}
	if(isset($_POST['karakter'])){
		$jatekosid = $_POST['karakter'];
	}
	
	if ($jatekid != 0) {
		$Jatek = new aJatek();
		$GameParams = $Jatek->getJatekosokEsJatekNev($jatekid);
		
		echo "<p id='jateknevesid'>".$GameParams['jatekneve']."(".$jatekid.")</p>";
		echo "<p id='player1'>".$GameParams['jatekos1']."(".$GameParams['userid1'].")</p>";
		echo "<p id='player2'>".$GameParams['jatekos2']."(".$GameParams['userid2'].")</p>";
		echo "<p id='player3'>".$GameParams['jatekos3']."(".$GameParams['userid3'].")</p>";
		echo "<p id='player4'>".$GameParams['jatekos4']."(".$GameParams['userid4'].")</p>";
		
		echo "<div id='aktplayer' style='visibility:hidden; width:0em; height:0em;'>".$jatekosid."</div>";
		echo "<div id='jatekid' style='visibility:hidden; width:0em; height:0em;'>".$jatekid."</div>";
	}
} 

function JatekosMentese(){
	
	if(isset($_POST['jatekid'])){
		$jatekID = $_POST['jatekid'];
	}
	
	if(isset($_POST['karakter'])){
		$jatekosID = $_POST['karakter'];
	}
	
	$Jatek = new aJatek();
	$szandek = "MENTES";
	$res = $Jatek->JatekosKarakterMentes($jatekosID, $jatekID,$szandek);
	if ($res === null) {
		altalanos::RedirectToURL("joinGame.php");
		exit;
	}else{
		$_SESSION['id_of_game'] = $jatekID;
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
    	  
    	  var karakter = document.getElementById("aktplayer").innerHTML;
    	  var jatekid = document.getElementById("jatekid").innerHTML;
    	  
    	  var kuldesDiv = document.getElementById("disconnectFormDiv").innerHTML = '<form id="disconnectForm" method="post" action="joinGame.php"><input id="szandek" name="szandek" type="hidden" value="BONTAS"><input id="jatekosid" name="jatekosid" type="hidden" value="'+karakter+'"><input id="jatekid" name="jatekid" type="hidden" value="'+jatekid+'"></form>';
          kuldes = document.getElementById("disconnectForm");
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
      Kapcsolódtál egy Játékohoz!
    </fieldset>
  
  <div style="overflow: auto; height: 25em; width: 11em; background-color: gray; float: left; margin: auto;">
    <table>
	  <tr><td><img src="./pictures/avatars/001.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/002.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img src="./pictures/avatars/003.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/004.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img src="./pictures/avatars/005.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/006.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img src="./pictures/avatars/007.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/008.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img src="./pictures/avatars/009.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/010.png" style="width: 70px; height: 70px"/></td></tr>
	  <tr><td><img src="./pictures/avatars/011.png" style="width: 70px; height: 70px"/></td><td><img src="./pictures/avatars/012.png" style="width: 70px; height: 70px"/></td></tr>
	</table>
  </div>
  
  <div style="width: 15em; height: 6em;  float: left; margin-left: 2em; padding-top: 0.6em; background-color: #9D919E;">
    
    <div id="jatektulajdonsagok" style="height: 15.5em; background-color: #D5C4CA;">
      A játék tulajdonságai:
     
     <?php JatekosMentese();?>
      
      <?php JatekosLista();?>
      
    </div>
    
  </div>
  
  
  <div style="width: 10em; height: 2em; float: left; margin-left: 2em; height: auto; background-color: #9D919E; padding: 0em; padding-top: 0.6em; text-align: center;">
    
    <div id="disconnectFormDiv" style="background-color: #D5C4CA; margin-bottom: 1em;">
      <button onclick="AdatKuldes();">BONTÁS</button>    
    </div>
    
    <div style="background-color: #D5C4CA;">
      <a href='refreshjoinedGame.php'>FRISSÍT</a>
    </div>
  </div>
  
  <label id="gamename" style="visibility: hidden;"></label>
  
  </fieldset>

  <div>
	<p style="font-size: x-small;">Szörnyes Társas &copy; 2013</p>
  </div>
  
</div>  
  


</body>
</html>
