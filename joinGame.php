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

if (isset($_POST["szandek"])){
	if ( ($_POST["szandek"]) === "BONTAS" ){
		$userid = $_SESSION["id_of_user"];
		
		$jatekosid = 0;
		if(isset($_POST["jatekosid"])){
			$jatekosid = $_POST["jatekosid"];
		}
		
		$jatekid = 0;
		if(isset($_POST["jatekid"])){
			$jatekid = $_POST["jatekid"];
		}
		
		$Jatek = new aJatek();
		$szandek = "BONTAS";
		$res = $Jatek->JatekbolKiszall($jatekosid,$userid,$jatekid,$szandek);
	}
}



function RejtettJatekJellemzok($adatok){

	if ($adatok === null) {
		
		$rejtettDiv = "<div id='rplayer1 style='visibility:visible;'>Játékos 1</div>";
		$rejtettDiv += "<div id='rplayer1 style='visibility:visible;'>Játékos 2</div>";
		$rejtettDiv += "<div id='rplayer1 style='visibility:visible;'>Játékos 3</div>";
		$rejtettDiv += "<div id='rplayer1 style='visibility:visible;'>Játékos 4</div>";
		
		echo $rejtettDiv; 
		return;
	}
	
	
	foreach ($adatok as $adat) {
		echo "<div id='rplayer1".$adat['jatekid']."' style='visibility:hidden; width:0em; height:0em;'>".$adat['jatekos1']."(".$adat['userid1'].")</div>";
		echo "<div id='rplayer2".$adat['jatekid']."' style='visibility:hidden; width:0em; height:0em;'>".$adat['jatekos2']."(".$adat['userid2'].")</div>";
		echo "<div id='rplayer3".$adat['jatekid']."' style='visibility:hidden; width:0em; height:0em;'>".$adat['jatekos3']."(".$adat['userid3'].")</div>";
		echo "<div id='rplayer4".$adat['jatekid']."' style='visibility:hidden; width:0em; height:0em;'>".$adat['jatekos4']."(".$adat['userid4'].")</div>";
	}
	echo "<div id='aktplayer' style='visibility:hidden; width:0em; height:0em;'></div>";
	echo "<div id='jatekid' style='visibility:hidden; width:0em; height:0em;'></div>";
}


function JatekLista(){
	$ttomb = array();
	$Jatek = new aJatek();
	/* Itt nem csak a játék listákat kérem le, de az adott játék összes jellemzőjét is
	 * játékosid-ket, userid-ket is. Ezeket használom fel a játékra kattintásoknál.*/	
	$ttomb = $Jatek->getJatekLista();
	$ttombdarab = count($ttomb);
	
	for ($i = 0; $i < $ttombdarab; $i++) {
		$j = $i + 1;
		$JatekJellemzok = $ttomb[$i]['jatekneve'].
						  "(".$ttomb[$i]['jatekid'].")";
		echo "<label id='JatekID$j' onclick='JatekValasztas($j)'>$JatekJellemzok</label><br/>";
	}
	return $ttomb;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-HU" lang="hu-HU">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Szörnyes Társas</title>
      
      <script type="text/javascript">

      function JatekValasztas(id){
            /*var elem = "JatekID"+id;
            ertek = document.getElementById(elem).innerHTML;
			alert(ertek);*/

    	  	allitott = false;
    	  	player = "";

    	  	var temp_id = 1;
    	  	var element = document.getElementById("JatekID"+temp_id);
    	  	while (true){
        	  	
				element.style.backgroundColor = "#D5C4CA";
				element.style.color = "black";
				temp_id += 1;
				
				element = document.getElementById("JatekID"+temp_id);
				if (element === null) {
					break;
				}
    	  	}
    	  	
    	  	document.getElementById("JatekID"+id).style.backgroundColor = "blue";
    	  	document.getElementById("JatekID"+id).style.color = "white";
    	    var szoveg = document.getElementById("JatekID"+id).innerHTML;
    	    var pos1 = szoveg.indexOf("(");
    	    pos1 += 1;
    	    var jatekneve = szoveg.substr(0,pos1-1);
    	    var pos2 = szoveg.indexOf(")");
			var hossz = pos2 - pos1;
			var jatekid = szoveg.substr(pos1,hossz);

			document.getElementById("jateknevesid").innerHTML = jatekneve;
			document.getElementById("jatekid").innerHTML = jatekid;
			document.getElementById("player1").innerHTML = document.getElementById("rplayer1"+jatekid).innerHTML; 
			document.getElementById("player2").innerHTML = document.getElementById("rplayer2"+jatekid).innerHTML;
			document.getElementById("player3").innerHTML = document.getElementById("rplayer3"+jatekid).innerHTML;
			document.getElementById("player4").innerHTML = document.getElementById("rplayer4"+jatekid).innerHTML;
			
      }

      function getID(){
          var karakter = document.getElementById("aktplayer").innerHTML;
          var jatekid = document.getElementById("jatekid").innerHTML;
          
          if (karakter == "") {
				alert("Nincs választott játékos!");
				exit;
			}

    		if (jatekid == "") {
    			alert("Nincs kitöltve a játék neve!");
				exit;
			}

			gameparams = new Array();
			gameparams[0] = karakter;
			gameparams[1] = jatekid;
			 
			return gameparams;
      }

      function AdatKuldes(){
          
    	  var GameParams 	= getID();
		  var karakterId 	= GameParams[0];	
    	  var JatekId 		= GameParams[1];
    	  var JatekNeve 	= document.getElementById("jateknevesid").innerHTML;
    	    
    	  var kuldesDiv = document.getElementById("joinFormDiv").innerHTML = '<form id="joinForm" method="post" action="joinedGame.php"><input id="karakter" name="karakter" type="hidden" value="'+karakterId+'"><input id="jatekid" name="jatekid" type="hidden" value="'+JatekId+'"><input id="jatekneve" name="jatekneve" type="hidden" value="'+JatekNeve+'"></form>';
          kuldes = document.getElementById("joinForm");
          if (kuldes) {                
				kuldes.submit();
			}
     }
      
      var allitott = false; 
      var player = "";
        function KepenKattintas(id){

        	var jatekosok = new Array();
        	jatekosok[0] = "player1";
        	jatekosok[1] = "player2";
        	jatekosok[2] = "player3";
        	jatekosok[3] = "player4";

        	if (!allitott){
        	for(i = 0; i < 4; i++){
            	player = document.getElementById(jatekosok[i]).innerHTML;
            	
               	var pos = player.search("0");
               	if (pos == 0){
                   	player = jatekosok[i];
                   	allitott = true;
                   	break;
               	}
            }
        	}
        		
            
            if ( id == 'pic001') {
            	document.getElementById(player).innerHTML = "Arthur Király";
            	document.getElementById("aktplayer").innerHTML = "1";
			} 
			if (id == "pic002") {
				document.getElementById(player).innerHTML = "Íjász Szamuráj";
				document.getElementById("aktplayer").innerHTML = "2";
			}
			if (id == "pic003"){
				document.getElementById(player).innerHTML = "Tűzes Íjász";
				document.getElementById("aktplayer").innerHTML = "3";
			}
			if (id == "pic004"){
				document.getElementById(player).innerHTML = "Alázatos Szamuráj";
				document.getElementById("aktplayer").innerHTML = "4";
			}
			if (id == "pic005"){
				document.getElementById(player).innerHTML = "Egyenes Lándzsa";
				document.getElementById("aktplayer").innerHTML = "5";
			}
			if (id == "pic006"){
				document.getElementById(player).innerHTML = "Harcos";
				document.getElementById("aktplayer").innerHTML = "6";
			}
			if (id == "pic007"){
				document.getElementById(player).innerHTML = "Lovag";
				document.getElementById("aktplayer").innerHTML = "7";
			}
			if (id == "pic008"){
				document.getElementById(player).innerHTML = "Női Bárdos";
				document.getElementById("aktplayer").innerHTML = "8";
			}
			if (id == "pic009"){
				document.getElementById(player).innerHTML = "Lángpallos Bestia";
				document.getElementById("aktplayer").innerHTML = "9";
			}
			if (id == "pic010"){
				document.getElementById(player).innerHTML = "Sisak Rejtély";
				document.getElementById("aktplayer").innerHTML = "10";
			}
			if (id == "pic011"){
				document.getElementById(player).innerHTML = "Hitman";
					document.getElementById("aktplayer").innerHTML = "11";
			}
			if (id == "pic012"){
				document.getElementById(player).innerHTML = "Tolkien";
				document.getElementById("aktplayer").innerHTML = "12";
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
      Válassz magadnak játékost és kapcsolódj egy Játékohoz!
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
  
  <div style="width: 15em; height: 6em;  float: left; margin-left: 2em; padding-top: 0.6em; background-color: #9D919E;">
  Játékok:
    <div style="overflow: auto; height: 6.4em; margin-bottom: 2em; background-color: #D5C4CA;">
      <?php $adatok = JatekLista();?>
    </div>
    
    <div id="jatektulajdonsagok" style="height: 15.5em; background-color: #D5C4CA;">
      A játék tulajdonságai:
      
      <?php RejtettJatekJellemzok($adatok);?>
      
      <p id="jateknevesid">
          Játék azonosító
      </p>
      
      <p id="player1">
          Játékos 1
      </p>
      
      <p id="player2">
          Játékos 2
      </p>
      
      <p id="player3">
          Játékos 3
      </p>
      
      <p id="player4">
          Játékos 4
      </p>
      
    </div>
    
  </div>
  
  
  <div style="width: 10em; height: 2em; float: left; margin-left: 2em; height: auto; background-color: #9D919E; padding: 0em; padding-top: 0.6em; text-align: center;">
    
    <div id="joinFormDiv" style="background-color: #D5C4CA; margin-bottom: 1em;">
      <button onclick="AdatKuldes();">KAPCSOLÓDÁS</button>    
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
