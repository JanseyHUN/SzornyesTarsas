<?php
header("Content-Type: text/html; charset=UTF-8");
require_once 'targy.php';

class AdatbazisKezelo{
  
    private $admin_email;
    private $from_address;
    private $sitename;
  
    private $db_host;
    private $jelszo;
    private $adatbazis;
    private $tablanev;
    private $kapcsolat;
    private $hibauzenet;
  
    private $error_message;
  
    //-----Initialization -------
    function __construct()
    {
      $this->sitename = 'http://fejesattila.ininet.hu/tarsas';
    }
    
    public function KapcsolatKeszites(){
    	$this->SetAdminEmail('csapo.janos@gmail.com');
    	$this->InitDB(	/*hostname*/"127.0.0.1",
    					/*username*/"fejesattila",
    					/*password*/"monor123",
    					/*database name*/"fejesattila",
    					/*table name*/"felhasznalok");
    }
    
    public function InitDB($host,$uname,$jelszo,$adatbazis,$tablanev)
    {
      $this->db_host        = $host;
      $this->felhasznalonev = $uname;
      $this->jelszo         = $jelszo;
      $this->adatbazis      = $adatbazis;
      $this->tablanev       = $tablanev;
  
    }
    
    function SetDataTable($table){
      $this->tablanev = $table;
    }
    
    function SetAdminEmail($email)
    {
      $this->admin_email = $email;
    }
    
    function SetWebsiteName($sitename)
    {
      $this->sitename = $sitename;
    }
    
    public function getSiteName(){
      return $this->sitename;
    }
    
    public function getFutoJatekListamDB(){
      $this->DBLogin();

      $userid = $_SESSION["id_of_user"];
      
      $qry = "SELECT id FROM jatek WHERE id IN (SELECT jatekid FROM jatekosok WHERE userid = ".$userid.")";
       
      $result = mysql_query($qry,$this->kapcsolat);
       
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
        return null;
      }
       
      $i = 0;
      $jatekid = null;
      while ($row = mysql_fetch_assoc($result)){
        if (!isset($row)){
          break;
        }
        $jatekid[$i] = $row["id"];
        $i++;  
      }
       
      return $jatekid;
     
    }
    
    public function EloJatekCheck($jatekid){
    	$this->DBLogin();
    	
    	$qry = "Select forduloszama from jatek where id=".$jatekid;
    	
    	$result = mysql_query($qry,$this->kapcsolat);
    	
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
    		return false;
    	}
    	
    	$row = mysql_fetch_assoc($result);
    	
    	if (!isset($row)){
    		return false;
    	}
    	
    	$forduloszama = $row["forduloszama"];
    	if ($forduloszama != 0) {
    		return true;
    	}
    	return false;
    }
    
    /*TÁRGYAK*/
    public function TargyakListazasa(){
      return $this->TargyakSelectDB(0);
    }
    
    public function TargyakMenteseByClass(targy $targy){
      if (!$this->TargyakIntoDBByClass($targy) ){
        $this->HandleError("A tárgyak elmentése sikertelen!");
        return false;
      }else {
        return true;
      }
    }
    
    public function TargyakMentese($targy){
      if (!$this->TargyakIntoDB($targy) ){
        $this->HandleError("A tárgyak elmentése sikertelen!");
        return false;
      }else {
        return true;
      }
    }
    
    private function TargyakSelectDB($id){
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
      
      if ($id == 0) {
        $qry = "SELECT * FROM targyak ORDER BY id ASC";
        
        $result = mysql_query($qry,$this->kapcsolat);
        
        if(!$result || mysql_num_rows($result) <= 0)
        {
          $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
          return "";
        }
        
        $i = 0;
        $targylista = array();
        while ($row = mysql_fetch_assoc($result)){
          
          $targylista[$i]["id"]         = $row["id"];
          $targylista[$i]["ar"]         = $row["ar"];
          $targylista[$i]["sebzesmin"]  = $row["sebzesmin"];
          $targylista[$i]["sebzesmax"]  = $row["sebzesmax"];
          $targylista[$i]["megnevezes"] = $row["megnevezes"];
          $targylista[$i]["hely"]       = $row["hely"];
          $targylista[$i]["kategoria"]  = $row["kategoria"];
          $targylista[$i]["hatashelye"] = $row["hatashelye"];
          $targylista[$i]["modosito"]   = $row["modosito"];
          $targylista[$i]["szint"]      = $row["szint"];
          $targylista[$i]["toltet"]     = $row["toltet"];
  
          $i++;
        }
      }
      return $targylista;
    }
    
    private function TargyakIntoDBByClass(targy $targy){
    	/*ar, sebzesmin,
    	 *sebzesmax, megnevezes,
    	*hely, kategoria,
    	*hatashelye, modosito,
    	*szint, toltet*/
    	 
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Adatbázisba bejelentkezés sikertelen!");
    		return false;
    	}
    	 
    	$qry = "INSERT INTO targyak 
    	        (ar,sebzesmin,sebzesmax,
    	         megnevezes,hely,kategoria,
    	         hatashelye,modosito,szint,toltet) VALUES(
    	".$targy->getAr().", ".$targy->getSebzesMin().",
    	".$targy->getSebzesMax().",
    	'".$targy->getMegnevezes()."',
    	".$targy->getHely().", ".$targy->getKategoria().",
    	".$targy->getHatasHelye().", ".$targy->getModosito().",
    	".$targy->getSzint().", ".$targy->getToltet().")";
    	 
    	 
    	$result = mysql_query($qry,$this->kapcsolat);
    	 
    	if ($result) {
    	  return true;
        }else{
          return false;
        }
    }
    
    private function TargyakIntoDB($targy){
    	/*ar, sebzesmin,
    	 *sebzesmax, megnevezes,
    	 *hely, kategoria,
    	 *hatashelye, modosito,
    	 *szint, toltet*/
        if ($targy == NULL) { return false; }
      
        if ( (gettype($targy[0]) !== "integer") && (gettype($targy[1]) !== "integer") &&
             (gettype($targy[2]) !== "integer") && (gettype($targy[3]) !== "string") &&
             (gettype($targy[4]) !== "integer") && (gettype($targy[5]) !== "integer") &&
             (gettype($targy[6]) !== "integer") && (gettype($targy[7]) !== "integer") && 
             (gettype($targy[8]) !== "integer") && (gettype($targy[9]) !== "integer") ){
          $this->HandleDBError("A tárgy típus egyeztetés nem megfelelő");
          return false;
        }
        
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Adatbázisba bejelentkezés sikertelen!");
    		return false;
    	}
    	
    	$qry = "INSERT INTO targyak
    	        (ar,sebzesmin,sebzesmax,
    	         megnevezes,hely,kategoria,
    	         hatashelye,modosito,szint,toltet) 
    	        VALUES(
    			$targy[0], $targy[1], $targy[2],
    			'".$targy[3]."',
    			$targy[4], $targy[5],
    			$targy[6], $targy[7],
    			$targy[8], $targy[9])";
    	
    	/*echo $qry;exit;*/
    	
    	$result = mysql_query($qry,$this->kapcsolat);
    	
    	if ($result) {
    		return true;
    	}else{
    		return false;
    	}
    }
    
    public function JatekInditas($jatekid){
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Adatbázisba bejelentkezés sikertelen!");
    		return false;
    	}
    	
    	$qry = "Update $this->tablanev Set forduloszama = (if(forduloszama = 0,1,forduloszama + 1)) where id=".$jatekid;
    	
    	if(!mysql_query( $qry ,$this->kapcsolat))
    	{
    		$this->HandleDBError("Sikertelen játékindítás");
    		return false;
    	}
    	return true;
    }
    
    public function clearJatekosFromJatek($jatekosid,$userid,$jatekid){
    	return $this->DeleteJatekosFromDB($jatekosid,$userid,$jatekid);
    }
    
    private function DeleteJatekosFromDB($jatekosid,$userid,$jatekid){
    	for ($i = 0; $i < 4; $i++) {
    		$jatekos = "jatekos"+$i;
    		$user = "userid"+$i;
    		$qry = "Update jatek set $jatekos = 0, $user = 0 where id=".$jatekid."AND jatekos1=".$jatekosid."AND userid1=".$userid;
    		if(!mysql_query( $qry ,$this->kapcsolat))
    		{
    			$this->HandleDBError("Sikertelen játékos törlés");
    			return false;
    		}
    	}
    	
    	
    	return true;
    }
    
    public function getJatekosokEsJatekNev($jatekid){
    	return $this->getJatekosokEsJatekNevFromDB($jatekid);
    }
    
    private function getJatekosokEsJatekNevFromDB($jatekid){
    	$this->DBLogin();
    	
    	$qry = "Select id, jatekneve, jatekos1, jatekos2, jatekos3, jatekos4, userid1, userid2, userid3, userid4 from jatek where id=".$jatekid;
    	
    	$result = mysql_query($qry,$this->kapcsolat);
    	
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
    		return null;
    	}
    	
    	$row = mysql_fetch_assoc($result);
    	
    	if (!isset($row)){
    		return null;
    	}
    	
    	$res['jatekid'] 	= $row['id'];
    	$res['jatekneve'] 	= $row['jatekneve'];
    	$res['jatekos1'] 	= $row['jatekos1'];
    	$res['jatekos2'] 	= $row['jatekos2'];
    	$res['jatekos3'] 	= $row['jatekos3'];
    	$res['jatekos4'] 	= $row['jatekos4'];
    	$res['userid1'] 	= $row['userid1'];
    	$res['userid2'] 	= $row['userid2'];
    	$res['userid3'] 	= $row['userid3'];
    	$res['userid4'] 	= $row['userid4'];
    	
    	return $res;
    }
    
    public function getJatekLista(){
    	return $this->getJatekListaFromDB();
    }
    
    private function getJatekListaFromDB(){
    	$this->DBLogin();    
    	
    	$qry = "SELECT jatekosok.id, jatekosok.userid, jatekosok.jatekid, IF((SELECT id FROM jatekosok WHERE id = ".$player_id." AND jatekid = ".$jatekid.") = ".$player_id.", 'true', 'false') AS bennevagyok FROM jatekosok, jatek WHERE jatekosok.jatekid = ".$jatekid." AND  jatek.id = jatekosok.jatekid AND jatek.forduloszama = 0 AND jatek.aktualisjatekosid = 0 GROUP BY bennevagyok";
    	
    	$result = mysql_query($qry,$this->kapcsolat);
    	
    	echo $result."<br/>";
    	echo $qry;exit;
    	
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
    		return "";
    	}
    	
    	$ttomb = array();
    	$i = 0;
    	while($row = mysql_fetch_assoc($result)){
    		$ttomb[$i]['jatekid'] 	= $row['id'];
    		$ttomb[$i]['jatekneve'] = $row['jatekneve'];
    		$ttomb[$i]['jatekos1'] 	= $row['jatekos1'];
    		$ttomb[$i]['jatekos2'] 	= $row['jatekos2'];
    		$ttomb[$i]['jatekos3'] 	= $row['jatekos3'];
    		$ttomb[$i]['jatekos4'] 	= $row['jatekos4'];
    		$ttomb[$i]['userid1'] 	= $row['userid1'];
    		$ttomb[$i]['userid2'] 	= $row['userid2'];
    		$ttomb[$i]['userid3'] 	= $row['userid3'];
    		$ttomb[$i]['userid4'] 	= $row['userid4'];
    		$i++;
    	}
    	
    	if (!isset($row)){
    		return null;
    	}
    	
    	return $ttomb;
    }
    
    /*TÉRKÉP*/
    
    public function TerkepBeolvasasa($id){
      return $this->TerkepSelectFromDB($id);
    }
    
    private function TerkepSelectFromDB($id){
      $this->DBLogin();
      
      $qry = "Select terkep from terkepek where id=".$id;
      
      $result = mysql_query($qry,$this->kapcsolat);
      
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
        return "";
      }
      
      $row = mysql_fetch_assoc($result);
      
      if (!isset($row)){
        return "";
      }
      
      return $row["terkep"];
    }
    
    public function TerkepBeolvasasaByPattern($terkepMinta){
      
      $this->DBLogin();
      
      $qry = "Select id from terkepek where terkep='".$terkepMinta."'";
      
      $result = mysql_query($qry,$this->kapcsolat);
      
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
        return "";
      }
      
      $row = mysql_fetch_assoc($result);
      
      if (!isset($row)){
        return "";
      }
      
      return $row["id"];
      
    }
    
    public function TerkepJatekbaMentese($terkepID){
      if (!$this->TerkepJatekIntoDB($terkepID) ){
        $this->HandleError("A térkép játékba hozása sikertelen!");
        return false;
      }else {
        return true;
      }
    }
    
    private function TerkepJatekIntoDB($terkepID){
    
      /*Alapértékekkel való feltöltés az idő, terkepid, jatekjelszo és userid kivételével
       * ezután már csak frissítéseket kell indítani*/
      
      if ($terkepID == NULL) {
        return false;
      }
    
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
      
      $jatekneve = $_POST["jatekneve"];
      $jatekjelszo = $_POST["jatekjelszo"];
      
      $datumido = date("Y-m-d H:i:s");
      $userid = $_SESSION['id_of_user'];
    
      $oszlopok = "(aktualisjatekosid, jatekneve, forduloszama, datum, terkepid, jelszo)";
      $ertekek = "0,'$jatekneve',0,'$datumido',".$terkepID.",'$jatekjelszo'";
      
      $qry = "INSERT INTO jatek ".$oszlopok." VALUES(".$ertekek.")";
      $result = mysql_query($qry,$this->kapcsolat);
       
      if ($result) {
        return true;
      }
      else{ return false;
      }
    }
    
    public function TerkepMentese($terkep){
      if (!$this->TerkepIntoDB($terkep) ){
        $this->HandleError("A térkép elmentése sikertelen!");
        return false;
      }else {
        return true;
      } 
    }
    
    public function getJatekos($sorszam,$jatekid){
      $jatekos = $this->getJatekosFromDB($sorszam,$jatekid);
      return $jatekos;
    }
    
    private function getJatekosFromDB($sorszam,$jatekid){
      $jatekosmezo = "jatekos".$sorszam;
      $usermezo = "userid".$sorszam;
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return null;
      }
      
      $query = "select $jatekosmezo, $usermezo from jatek where id=".$jatekid;
      
      $result = mysql_query($query,$this->kapcsolat);
      
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a játék nevének lekérdezésekor");
        return null;
      }
      
      $row = mysql_fetch_assoc($result);
      
      if (!isset($row)){
        return null;
      }
      
      $res[0] = $row[$jatekosmezo];
      $res[1] = $row[$usermezo];
      return $res;
    }

    public function JatekbolKiszall($jatekosid, $userid, $jatekid, $szandek){
    	$res = $this->JatekosUpdateDBByJatekId($jatekosid, $jatekid, $szandek);
    	return $res;
    }
    
    public function getJatekID($terkepID){
      $jatekid = $this->getJatekIDFromDB($terkepID);
      return $jatekid;
    }
    
    public function getJatekNeve($terkepID){
       $jatekneve = $this->getJatekNeveFromDB($terkepID);
       return $jatekneve;
    }
    
    private function getJatekIDFromDB($terkepID){
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
    
      $query = "select id from jatek where terkepid=".$terkepID;
    
      $result = mysql_query($query,$this->kapcsolat);
    
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a játék nevének lekérdezésekor");
        return "";
      }
    
      $row = mysql_fetch_assoc($result);
    
      if (!isset($row)){
        return "";
      }
    
      $jatekid = $row["id"];
      return $jatekid;
    
    }
    
    private function getJatekNeveFromDB($terkepID){
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
      
      $query = "select jatekneve from jatek where terkepid=".$terkepID;

      $result = mysql_query($query,$this->kapcsolat);
      
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a játék nevének lekérdezésekor");
        return "";
      }
      
      $row = mysql_fetch_assoc($result);
      
      if (!isset($row)){
        return "";
      }
      
      $jatekneve = $row["jatekneve"];
      return $jatekneve;
      
    }
      
    public function JatekosMenteseToDB($jatekosID, $jid, $szandek){    	
    	$res = $this->JatekosUpdateDBByJatekId($jatekosID, $jid, $szandek);
    	if ( $res == null ){
    		$this->HandleError("A játékos elmentése sikertelen!");
    		return null;
    	}else {
    		return $res;
    	}
    }
    
    public function JatekosMentese($karakter, $terkepID){
      $res = $this->JatekosUpdateDB($karakter, $terkepID);
      if ( $res === "" ){
        $this->HandleError("A játékos elmentése sikertelen!");
        return "";
      }else {
        return $res;
      }
    }
    
    private function JatekosLeirUpdateHelyenkentByJatekId($jatekos,$jatekosID,$jid,$user){
    	$aktUser = $_SESSION["id_of_user"];
    	
    	/* Parameterek: jatekos - jatekos1,jatekos2... string
    	 * 				jatekosID - egyedi azonosito
    	 * 				jid - ez a jatek egyedi azonositoja
    	 * 				user - userid1, userid2... ez is string
    	 * Bontja, avagy kinullazza annak a jatekosnak a jatek kapcsolatat, 
    	 * aki a jatekosid és a jatekosuser alatt van a jatekid szerint, igy ez a 
    	 * jatekos mar ezutan nem listazodik a jatekban*/
    	$query = "update jatek set $jatekos = (if( $jatekos = $jatekosID AND $user = $aktUser,0,$jatekos)), $user = (if($user = $aktUser AND $jatekos = 0, 0, $user)) where id =".$jid;
    	 
    	if(!mysql_query( $query ,$this->kapcsolat))
    	{
    		$this->HandleDBError("Sikertelen játékos frissítés");
    		return false;
    	}
    	
    	if (mysql_affected_rows() == 0) {
    		return false;
    	} 
    	
    	/*Itt vissza false-al kell térnie, ha nem ok az update*/
    	
    	return true;
    }
    
    private function JatekosFelveszUpdateHelyenkentByJatekId($jatekos, $jatekosID, $jid, $user){
    	$aktUser = $_SESSION["id_of_user"];
    	$query = "update jatek set $jatekos = (if( ($jatekos = 0) AND (($user = 0) OR ($user = $aktUser)), $jatekosID, $jatekos)), $user = (if( ($jatekos = $jatekosID) AND ($user = 0), $aktUser, $user)) where id =$jid";
    	
    	if(!mysql_query( $query ,$this->kapcsolat))
    	{
    		$this->HandleDBError("Sikertelen játékos frissítés");
    		return false;
    	}
    	
    	$query = "select $jatekos,$user from jatek where id =".$jid;
    	if(!mysql_query( $query ,$this->kapcsolat))
    	{
    		$this->HandleDBError("Sikertelen játékos frissítés");
    		return false;
    	}
    	$result = mysql_query($query,$this->kapcsolat);
    	
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
    		return false;
    	}
    	
    	$row = mysql_fetch_assoc($result);
    	
    	if (!isset($row)){
    		return false;
    	}
    	
    	/* Ha a lekerdezeskor egyeznek a mezőkben talált értékek, akkor sikeres a rögzítés,
    	 * ha ezek különböznek, akkor false értékkel tér vissza*/
    	if ( ($row[$jatekos] === $jatekosID) && ($row[$user] === $_SESSION["id_of_user"]) ){
    		return true;
    	}
    	return false;
    }
    
    private function JatekosUpdateHelyenkent($jatekos,$karakter,$terkepID,$user){
      $query = "update jatek set $jatekos = (if($jatekos = 0,$karakter,$jatekos)), userid1 = (if($jatekos = $karakter, $user, userid1)) where terkepid =".$terkepID;
      
      if(!mysql_query( $query ,$this->kapcsolat))
      {
        $this->HandleDBError("Sikertelen játékos frissítés");
        return false;
      }
      
      $query = "select $jatekos,$user from jatek where terkepid =".$terkepID;
      if(!mysql_query( $query ,$this->kapcsolat))
      {
        $this->HandleDBError("Sikertelen játékos frissítés");
        return false;
      }
      $result = mysql_query($query,$this->kapcsolat);
    
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
        return false;
      }
    
      $row = mysql_fetch_assoc($result);
    
      if (!isset($row)){
        return false;
      }
      
      /* Ha a lekerdezeskor egyeznek a mezőkben talált értékek, akkor sikeres a rögzítés,
       * ha ezek különböznek, akkor false értékkel tér vissza*/
      if ( ($row[$jatekos] === $karakter) && ($row[$user] === $_SESSION["id_of_user"]) ){
        return true;
      }
      return false;
    }
    
    private function JatekosUpdateDBByJatekId($jatekosID, $jid, $szandek){
      
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Adatbázisba bejelentkezés sikertelen!");
    		return "";
    	}
    
    	/*Mivel max 4 játékos játszhat így végigmegy a kód mind a 4 játékos lehetőségen
    	 * és ha talál olyan adatbázismezőt, ahol még nincs kitöltve játékod id-vel,
    	* akkor belerakja. Ha van már jatekosid, akkor a következővel próbálkozik*/
    	for ($i = 0; $i < 4; $i++) {
    		$jatekos = "";
    		$user    = "";
    
    		switch ($i) {
    			case 0: $jatekos = "jatekos1"; $user = "userid1";
    			break;
    			case 1: $jatekos = "jatekos2"; $user = "userid2";
    			break;
    			case 2: $jatekos = "jatekos3"; $user = "userid3";
    			break;
    			case 3: $jatekos = "jatekos4"; $user = "userid4";
    			break;
    			default: $jatekos = "jatekos1"; $user = "userid1";
    			break;
    		}
    
    		/* Ha sikeres a frissítés, akkor true a result, ha nem sikeres, mert már van
    		 * játékos kód a mezőben, akkor a result false*/
    		if ($szandek === "MENTES") {
    			$res = $this->JatekosFelveszUpdateHelyenkentByJatekId($jatekos,$jatekosID,$jid,$user);
    		}
    		if ($szandek === "BONTAS") {
    			$res = $this->JatekosLeirUpdateHelyenkentByJatekId($jatekos,$jatekosID,$jid,$user);
    		}
    		
    
    		if ($res) {
    			return $jatekosID;
    		}
    	}
    	return null;
    }
    
    
    private function JatekosUpdateDB($karakter, $terkepID){
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return "";
      }
      
      /*Mivel max 4 játékos játszhat így végigmegy a kód mind a 4 játékos lehetőségen
       * és ha talál olyan adatbázismezőt, ahol még nincs kitöltve játékod id-vel,
       * akkor belerakja. Ha van már jatekosid, akkor a következővel próbálkozik*/
      for ($i = 0; $i < 4; $i++) {
        $jatekos = "";
        $user    = "";
        
        switch ($i) {
          case 0: $jatekos = "jatekos1"; $user = "userid1";
            break;
          case 1: $jatekos = "jatekos2"; $user = "userid2";
            break;
          case 2: $jatekos = "jatekos3"; $user = "userid3";
            break;
          case 3: $jatekos = "jatekos4"; $user = "userid4";
            break;
          default:; $jatekos = "jatekos1"; $user = "userid1";
            break;
        }
        
        /* Ha sikeres a frissítés, akkor true a result, ha nem sikeres, mert már van
         * játékos kód a mezőben, akkor a result false*/
        $res = $this->JatekosUpdateHelyenkent($jatekos,$karakter,$terkepID,$user);
        
        if ($res) { return $karakter; }
        return "";
      }
    }
    
    
    private function TerkepIntoDB($terkep){
      
      if ($terkep == NULL) {
        return false;
      }
    
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
      
      $qry = "INSERT INTO terkepek (terkep) VALUES('".$terkep."')";
        $result = mysql_query($qry,$this->kapcsolat);
         
        if ($result) { return true; }
          else{ return false;}
    }
    
    
    public function getJatekosPozicio(&$adatok){
      
      $this->DBLogin();
      
      $oszlop = $adatok["oszlop"];
      $sor = $adatok["sor"];
      $jatekosid = $adatok["jatekosid"];
    
      $qry = "Select pososzlop, possor from jatekosok where id=".$jatekosid;
      
      $result = mysql_query($qry,$this->kapcsolat);
    
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
        return "";
      }
    
      $row = mysql_fetch_assoc($result);
    
      if (!isset($row)){
        return "";
      }
      
      $adatok["oszlop"] = $row["pososzlop"];
      $adatok["sor"]    = $row["possor"];
    }
    
    private function DBLogin()
    {
      $this->kapcsolat = mysql_connect($this->db_host,$this->felhasznalonev,$this->jelszo);
    
      if(!$this->kapcsolat)
      {
        $this->HandleDBError("Adatbázisba bejelentkezés sikertelen! Belelentkezési jogosultságok rendben vannak?");
        return false;
      }
      if(!mysql_select_db($this->adatbazis, $this->kapcsolat))
      {
        $this->HandleDBError('Sikertelen adatbázis választás: '.$this->adatbazis.' A megadott adatbázis létezik, illetve pontosan van megadva?');
        return false;
      }
      if(!mysql_query("SET NAMES 'UTF8'",$this->kapcsolat))
      {
        $this->HandleDBError('Hiba az utf-8 kódolás megadásánál');
        return false;
      }
      return true;
    }
    
    function HandleError($err)
    {
      $this->hibauzenet .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
      $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    
    function Login()
    {
      if(empty($_POST['username']))
      {
        $this->HandleError("A felhszanálónév üres!");
        return false;
      }
    
      if(empty($_POST['jelszo']))
      {
        $this->HandleError("A Jelszó üres!");
        return false;
      }
    
      $felhasznalonev = trim($_POST['username']);
      $jelszo = trim($_POST['jelszo']);
    
      if(!isset($_SESSION)){
        session_start();
      }
      if(!$this->CheckLoginInDB($felhasznalonev,$jelszo))
      {
        return false;
      }
    
      $_SESSION[altalanos::GetLoginSessionVar()] = $felhasznalonev;
    
      return true;
    }
    
    function CheckLoginInDB($felhasznalonev,$jelszo)
    {
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return false;
      }
    
      $felhasznalonev = altalanos::SanitizeForSQL($felhasznalonev);
      $pwdmd5 = md5($jelszo);
      
      $qry = "Select username, email, id from $this->tablanev where username='$felhasznalonev' and pass='$pwdmd5'";
      
      $result = mysql_query($qry,$this->kapcsolat);
    
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Hiba a bejelentkezeskor. Hibas felhasznalonev, vagy jelszo");
        return false;
      }
    
      $row = mysql_fetch_assoc($result);
    
    
      $_SESSION['name_of_user']  = $row['username'];
      $_SESSION['email_of_user'] = $row['email'];
      $_SESSION['id_of_user'] 	 = $row['id'];
    
      return true;
    }
    
    function RegisterUser()
    {
      if(!isset($_POST['submitted']))
      {
        return false;
      }
    
      $formvars = array();
    
      altalanos::RegisztraciosAdatokOsszegyujtese($formvars);
      if(!$this->UserInsertToDBElozetes($formvars)){
        return false;
      }
    
      /*if(!$this->SendUserConfirmationEmail($formvars)){ return false; }*/
    
      /*$this->SendAdminIntimationEmail($formvars);*/
    
      return true;
    }
    
    public function GetErrorMessage()
    {
      if(empty($this->hibauzenet))
      {
        return '';
      }
      $errormsg = nl2br(htmlentities($this->hibauzenet));
      return $errormsg;
    }
    
    function ChangePasswordInDB($user_rec, $newpwd)
    {
      $newpwd = altalanos::SanitizeForSQL($newpwd);
    
      $qry = "Update felhasznalok Set pass='".md5($newpwd)."' Where  id=".$user_rec['id']."";
      
      if(!mysql_query( $qry ,$this->kapcsolat))
      {
        $this->HandleDBError("Sikertelen jelszó frissítés \nquery:$qry");
        return false;
      }
      return true;
    }
    
    function GetUserFromEmail($email,&$user_rec)
    {
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelenkezés sikertelen!");
        return false;
      }
      $email = altalanos::SanitizeForSQL($email);
    
      $result = mysql_query("Select * from felhasznalok where email='$email'",$this->kapcsolat);
    
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Nincs felhasználó ezzel az email címmel: $email");
        return false;
      }
      $user_rec = mysql_fetch_assoc($result);
    
      return true;
    }
    
    function UserInsertToDBElozetes(&$formvars)
    {
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázishoz csatlakozás nem sikerült!");
        return false;
      }
    
      if(!$this->IsFieldUnique($formvars,'username'))
      {
        $this->HandleError("A felhasznalonev mar foglalt! Kerlek adj meg masikat!");
        return false;
      }
      
      if(!$this->IsFieldUnique($formvars,'email'))
      {
        $this->HandleError("Ez az email mar foglalt");
        return false;
      }
    
    
      if(!$this->UserInsertIntoDB($formvars))
      {
        $this->HandleError("Adatbázisba tétel nem sikerült!");
        return false;
      }
      return true;
    }
    
    private function IsFieldUnique($formvars,$fieldname)
    {
      $field_val = altalanos::SanitizeForSQL($formvars[$fieldname]);
      $qry = "select id from felhasznalok where $fieldname='".$field_val."'";
      $result = mysql_query($qry,$this->kapcsolat);
      if($result && mysql_num_rows($result) > 0)
      {
        return false;
      }
      return true;
    }
    
    function UserInsertIntoDB(&$formvars)
    {
      $Date = date("Y.m.d h:m:s");
      $ElsoUtsoBelepes = "0000.00.00 00:00:00";
      $insert_query = 'insert into felhasznalok(
      fullname,
      username,
      pass,
      regido,
      utolsobelepes,
      email
      )
      values(
      "' . altalanos::SanitizeForSQL($formvars['teljesnev']) . '",
      "' . altalanos::SanitizeForSQL($formvars['username']) . '",
      "' . md5($formvars['jelszo']) . '",
      "' . altalanos::SanitizeForSQL($Date) . '",
      "' . altalanos::SanitizeForSQL($ElsoUtsoBelepes) . '",
      "' . altalanos::SanitizeForSQL($formvars['email']) . '"
      )';
      if(!mysql_query( $insert_query ,$this->kapcsolat))
      {
        $this->HandleDBError("Hiba az adatok táblába helyezésekor(INSERT)\nquery:$insert_query");
        return false;
      }
      return true;
    }
  
}
?>