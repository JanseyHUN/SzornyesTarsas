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
    
    
    public function TerkepMentese($terkep){
      if (!$this->TerkepIntoDB($terkep) ){
        $this->HandleError("A térkép elmentése sikertelen!");
        return false;
      }else {
        return true;
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