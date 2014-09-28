<?php
header("Content-Type: text/html; charset=UTF-8");
ob_start();
/*include '/wwwroot/tarsas/include/altalanos.php';*/ 
/*include '/wwwroot/tarsas/map/map_fixedbyJani.php';*/
/*include '/wwwroot/tarsas/db.php';*/
ob_end_clean();
$altalanos = new altalanos();
$db = new AdatbazisKezelo();

/*$_SESSION["id_of_player"] - a kiválasztott karakter a tumble képekből*/
/*$_SESSION["id_of_user"]   - a felhasználó id-je */
/*$_SESSION["id_of_game"]   - a játék egyedi azonosítója */
/*$_SESSION["id_of_map"]    - a térkép egyedi azonosítója*/

/*Egyelőre ennyi a Session-be elmentett összes fontos változó*/

class aJatek
{
	
    public function getFutoJatekListam(){
      $db = new AdatbazisKezelo();
      $db->KapcsolatKeszites();
      $db->SetDataTable("jatek");
      
      $res = $db->getFutoJatekListamDB();
      if ($res == null) {
        echo $db->GetErrorMessage();
      }
      return $res;
    }
    
  
	public function JatekIndithatosagEllenorzes(){
		$db = new AdatbazisKezelo();
		$db->KapcsolatKeszites();
		$db->SetDataTable("jatek");
		
		$jatekid = $_SESSION["id_of_game"];
		$res = $db->EloJatekCheck($jatekid);
		if (!$res) {
			echo $db->GetErrorMessage();
		}
		return $res;
		
	}
	
	public function JatekStartFlag($mehet,$jatekid) {
		$db = new AdatbazisKezelo();
		$db->KapcsolatKeszites();
		$db->SetDataTable("jatek");
		
		$res = $db->JatekInditas($jatekid);
		if (!$res) {
			echo $db->GetErrorMessage();
		}
		return $res;
		
	} 
	
	public function JatekbolKiszall($jatekosid,$userid,$jatekid,$szandek){
		$db = new AdatbazisKezelo();
		$db->KapcsolatKeszites();
		$db->SetDataTable("jatek");
			
		$res = $db->JatekbolKiszall($jatekosid, $userid, $jatekid, $szandek);
		if (!$res) {
			echo $db->GetErrorMessage();
		} 
		return $res;
	}
	
	public function getJatekosokEsJatekNev($jatekid){
		$db = new AdatbazisKezelo();
		$db->KapcsolatKeszites();
		$db->SetDataTable("jatek");
		 
		$res = $db->getJatekosokEsJatekNev($jatekid);
		if ($res === null) {
			echo $db->GetErrorMessage();
			return null;
		} else {
			return $res;
		}	
	}
	
  public function getJatekLista(){
  	$db = new AdatbazisKezelo();
  	$db->KapcsolatKeszites();
  	$db->SetDataTable("jatek");
  	
  	$res = $db->getJatekLista();
  	if ($res === null) {
  		echo $db->GetErrorMessage();
  		return null;
  	} else {
  		return $res;
  	}
  }
	
  public function JatekosKarakterMentes($jatekosID,$jatekID, $szandek){
  	
  	$res = $this->JatekosMenteseByJatekId($jatekosID, $jatekID, $szandek);
  	if ($res === null){
  		return null;
  	}else {
  		return $res."(".$_SESSION["id_of_user"].")";
  	}
  	
  }
  
  private function JatekosMenteseByJatekId($jatekosID, $jatekID, $szandek){
  	$db = new AdatbazisKezelo();
  	$db->KapcsolatKeszites();
  	$db->SetDataTable("jatek");
  
  	$jid = $jatekID;
  	$res = $db->JatekosMenteseToDB($jatekosID, $jid, $szandek);
  	if ($res == null) {
  		echo $db->GetErrorMessage();
  		return null;
  	} else {
  		return $res;
  	}
  }
  
  
  public function JatekosKarakterMentesEsKiiratas($terkepID){
    
    if(empty($_POST["karakter"])){
      return "Hibás Karakter!";
    }
        
    $valasztottKarakter = $_POST["karakter"];
    /*session-be kell menteni a választott karaktert*/
    $_SESSION["id_of_player"] = $valasztottKarakter;
    
    $res = $this->JatekosMentese($valasztottKarakter, $terkepID);
    return $res."(".$_SESSION["id_of_user"].")";
    
    
  }
  
  private function JatekosMentese($karakter, $terkepID){
    $db = new AdatbazisKezelo();
    $db->KapcsolatKeszites();
    $db->SetDataTable("jatek");
    
    $res = $db->JatekosMentese($karakter, $terkepID);
    if ($res === "") {
      echo $db->GetErrorMessage();
      return "";
    } else {
      return $res;
    }
  }
  
  public function getJatekNeve($terkepID){
    $db = new AdatbazisKezelo();
    $db->KapcsolatKeszites();
    $db->SetDataTable("jatek");
    
    $jatekneve = $db->getJatekNeve($terkepID);
    return $jatekneve;
  }
  
  public function getJatekos($sorszam,$jatekid){
    $db = new AdatbazisKezelo();
    $db->KapcsolatKeszites();
    $db->SetDataTable("jatek");
    
    $jatekos = $db->getJatekos($sorszam,$jatekid);
    return $jatekos;
  }
  
  public function getJatekID($terkepID){
    $db = new AdatbazisKezelo();
    $db->KapcsolatKeszites();
    $db->SetDataTable("jatek");
    
    $jatekid = $db->getJatekID($terkepID);
    return $jatekid;
  }
  
  public function JatekGeneralasEsMentes(){
    $terkepid = TerkepGeneralasEsMentes();
    
    $jatekid = $this->getJatekID($terkepid);
    $_SESSION["id_of_game"] = $jatekid;
    
    return $terkepid;
  }
  
  public function TerkepAJatekba($terkepID){
    $db = new AdatbazisKezelo();
    $db->KapcsolatKeszites();
    $db->SetDataTable("jatek");
    
    $res = $db->TerkepJatekbaMentese($terkepID);
    if (!$res) {
      echo $db->GetErrorMessage();
    }
  }
  
}

?>