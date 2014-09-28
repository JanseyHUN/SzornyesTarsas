<?PHP
header("Content-Type: text/html; charset=UTF-8");
require_once "formvalidator.php";
require_once "class.phpmailer.php";

class altalanos
{
    private static $rand_key = '0iQx5oBk66oVZep';
    private static $error_message;
    //------- Fő Blokk ----------------------
    
    
    public function DetectBrowser(){
      $firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
      $chrome  = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
      $msie    = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
      
      if ($firefox){
        $browser = "firefox";
      } elseif ($chrome){
        $browser = "chrome";
      } elseif ($msie){
        $browser = "msie";
      }
      return $browser;
    }

    function IDSzetvalasztas(&$formvars){
      $formvars['muvelet'] = substr($formvars['submitted'],0,1);
      $pos = strpos($formvars['submitted'], '-');
      $formvars['vizsgaid'] = substr($formvars['submitted'],1,$pos -1);
      $tanuloidhossz = strlen($formvars['submitted']) - $pos;
      $formvars['tanuloid'] = substr($formvars['submitted'],$pos + 1,$tanuloidhossz);
    }
    
    
    function InsertIntoVizsgaraJelentkezesDB($formvars){
      
      $insert_query = 'insert into '.$this->tablanev.'(vizsgaid, tanuloid) values ('.$formvars['vizsgaid'].','.$formvars['tanuloid'].')';
      
      if(!mysql_query( $insert_query ,$this->kapcsolat))
      {
        $this->HandleDBError("Hiba az adatok táblába helyezésekor(INSERT)\nquery:$insert_query");
        return false;
      }
      return true;
    }
    
    function JegyLekerdezes($vizsgaid){
      
      $qry = "Select jegy from vizsgarajelentkezesek where vizsgaid=".$vizsgaid." AND tanuloid=".$_SESSION['id_user'];
      
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
      return $row['jegy'];
    }
    
    function getVizsgaKerdesek($formvars){
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelenkezés sikertelen!");
        return false;
      }

      $query = "SELECT vizsgatipus, kerdesid FROM vizsgak WHERE id=".$formvars['vizsgaid'];
      
      $result = mysql_query($query,$this->kapcsolat);
      
      if(!$result || mysql_num_rows($result) <= 0)
      {
        $this->HandleError("Nem található a vizsga");
        return false;
      }
      
      $row = mysql_fetch_assoc($result);
      
      $formvars['vizsgatipus'] = $row['vizsgatipus'];
      $formvars['kerdesid'] = $row['kerdesid'];
      
      
      
      $teszt = new tesztek();

      switch ($formvars['vizsgatipus']) {
        case 0: $query = "SELECT * FROM kivalasztoskerdesek WHERE id in(".$formvars['kerdesid'].")";
                $result = mysql_query($query,$this->kapcsolat);
                if(!$result || mysql_num_rows($result) <= 0){ $this->HandleError("Nem található a vizsga"); return false; }
                $htmlresz .= "<fieldset style='margin-top: 10px; background-color: #F2EDDE; margin-bottom: 20px; width: 40em;'><legend>Kiválasztós vizsga</legend>";
                $htmlresz .= "<form name='kivvizsga' action=vizsgavege.php method='post' accept-charset='UTF-8'>";
                $htmlresz .= "<input type='hidden' name='userid' value='".$formvars['userid']."'/>";
                $htmlresz .= "<input type='hidden' name='vizsgaid' value='".$formvars['vizsgaid']."'/>";
                $htmlresz .= "<input type='hidden' name='submitted' value='0'/>";
                $htmlresz .= "<input type='hidden' name='kerdesid' value='".$formvars['kerdesid']."'/>";
                while ($rows = mysql_fetch_assoc($result)){
                  $htmlresz .= $teszt->displayKivalasztosVizsga($rows);
                }
                
                $htmlresz .= "<BR/><input type='submit' name='submit' value='Kész'></form></fieldset>";
                
                $htmlresz .= "<!-- Alább található az erre az oldalra vonatkozó validátor -->";
                $htmlresz .= "<script type='text/javascript'>";
                
                $htmlresz .= "var frmvalidator  = new Validator('kivvizsga');";
                $htmlresz .= "frmvalidator.EnableOnPageErrorDisplay();";
                $htmlresz .= "frmvalidator.EnableMsgsTogether();";
                
                $kerdesarr = explode(",", $formvars['kerdesid']);
                
                for ($i = 0; $i < count($kerdesarr); $i++) {
                  $htmlresz .= "frmvalidator.addValidation('kerdes".$kerdesarr[$i]."','selone_radio','Valamit ki kell választani!');";
                }
                
                $htmlresz .= "</script>";
                
                
                echo $htmlresz;
          break;
        case 1: $query = "SELECT * FROM igazhamiskerdesek WHERE id in(".$formvars['kerdesid'].")";
                $result = mysql_query($query,$this->kapcsolat);
                if(!$result || mysql_num_rows($result) <= 0){ $this->HandleError("Nem található a vizsga"); return false; }
                $htmlresz .= "<fieldset style='margin-top: 10px;  background-color: #F2EDDE; margin-bottom: 20px;  width: 40em;'><legend>Igaz Hamis vizsga</legend>";
                $htmlresz .= "<form name='ihvizsga' action=vizsgavege.php method='post' accept-charset='UTF-8'>";
                $htmlresz .= "<input type='hidden' name='userid' value='".$formvars['userid']."'/>";
                $htmlresz .= "<input type='hidden' name='vizsgaid' value='".$formvars['vizsgaid']."'/>";
                $htmlresz .= "<input type='hidden' name='submitted' value='1'/>";
                $htmlresz .= "<input type='hidden' name='kerdesid' value='".$formvars['kerdesid']."'/>";
                while ($rows = mysql_fetch_assoc($result)){
                  $htmlresz .= $teszt->displayIgazHamisVizsga($rows)."<BR/>";
                }
                $htmlresz .= "<BR/><input type='submit' name='submit' value='Kész'></form></fieldset>";
                
                
                $htmlresz .= "<!-- Alább található az erre az oldalra vonatkozó validátor -->";
                $htmlresz .= "<script type='text/javascript'>";
                
                $htmlresz .= "var frmvalidator  = new Validator('ihvizsga');";
                $htmlresz .= "frmvalidator.EnableOnPageErrorDisplay();";
                $htmlresz .= "frmvalidator.EnableMsgsTogether();";
                
                $kerdesarr = explode(",", $formvars['kerdesid']);
                
                for ($i = 0; $i < count($kerdesarr); $i++) {
                  $htmlresz .= "frmvalidator.addValidation('kerdes".$kerdesarr[$i]."','selone_radio','Valamit ki kell választani!');";
                }
                
                $htmlresz .= "</script>";
                
                
                
                echo $htmlresz;
          break;
        case 2: $query = "SELECT * FROM kiegeszitoskerdesek WHERE id in(".$formvars['kerdesid'].")";
                $result = mysql_query($query,$this->kapcsolat);
                if(!$result || mysql_num_rows($result) <= 0){ $this->HandleError("Nem található a vizsga"); return false; }
                $htmlresz .= "<fieldset style='margin-top: 10px; background-color: #F2EDDE; margin-bottom: 20px; width: 40em;'><legend>Kiegészítős vizsga</legend>";
                $htmlresz .= "<form name='kiegvizsga' action=vizsgavege.php method='post' accept-charset='UTF-8'>";
                $htmlresz .= "<input type='hidden' name='userid' value='".$formvars['userid']."'/>";
                $htmlresz .= "<input type='hidden' name='vizsgaid' value='".$formvars['vizsgaid']."'/>";
                $htmlresz .= "<input type='hidden' name='submitted' value='2'/>";
                $htmlresz .= "<input type='hidden' name='kerdesid' value='".$formvars['kerdesid']."'/>";
                while ($rows = mysql_fetch_assoc($result)){
                  $htmlresz .= $teszt->displayKiegeszitosVizsga($rows)."<BR/>";
                }
                $htmlresz .= "<BR/><input type='submit' name='submit' value='Kész'></form></fieldset>";
                
                
                $htmlresz .= "<!-- Alább található az erre az oldalra vonatkozó validátor -->";
                $htmlresz .= "<script type='text/javascript'>";
                
                $htmlresz .= "var frmvalidator  = new Validator('kiegvizsga');";
                $htmlresz .= "frmvalidator.EnableOnPageErrorDisplay();";
                $htmlresz .= "frmvalidator.EnableMsgsTogether();";
                
                $kerdesarr = explode(",", $formvars['kerdesid']);
                
                for ($i = 0; $i < count($kerdesarr); $i++) {
                  $htmlresz .= "frmvalidator.addValidation('kerdes".$kerdesarr[$i]."','req','Töltsd ki a mezőt!');";
                }
                
                $htmlresz .= "</script>";
                
                echo $htmlresz;
          break;
        case 3: $query = "SELECT * FROM parositoskerdesek WHERE id in(".$formvars['kerdesid'].")";
                $result = mysql_query($query,$this->kapcsolat);
                if(!$result || mysql_num_rows($result) <= 0){ $this->HandleError("Nem található a vizsga"); return false; }
                $htmlresz .= "<fieldset style='margin-top: 10px; background-color: #F2EDDE; margin-bottom: 20px; width: 40em;'><legend>Kiválasztós vizsga</legend>";
                $htmlresz .= "<form id='pvizsga' name='pvizsga' action=vizsgavege.php method='post' accept-charset='UTF-8'>";
                $htmlresz .= "<input type='hidden' name='userid' value='".$formvars['userid']."'/>";
                $htmlresz .= "<input type='hidden' name='vizsgaid' value='".$formvars['vizsgaid']."'/>";
                $htmlresz .= "<input type='hidden' name='submitted' value='3'/>";
                $htmlresz .= "<input type='hidden' name='kerdesid' value='".$formvars['kerdesid']."'/>";
                while ($rows = mysql_fetch_assoc($result)){
                 $htmlresz .= $teszt->displayParositosVizsga($rows)."<BR/>";
                }
                $htmlresz .= "<BR/><input type='submit' name='submit' value='Kész'></form></fieldset>";
                
                $htmlresz .= "<!-- Alább található az erre az oldalra vonatkozó validátor -->";
                $htmlresz .= "<script type='text/javascript'>";
                
                $htmlresz .= "var frmvalidator  = new Validator('pvizsga');";
                $htmlresz .= "frmvalidator.EnableOnPageErrorDisplay();";
                $htmlresz .= "frmvalidator.EnableMsgsTogether();";
                
                $kerdesarr = explode(",", $formvars['kerdesid']);
                
                for ($i = 0; $i < count($kerdesarr); $i++) {
                  $htmlresz .= "frmvalidator.addValidation('parositasok1-1-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok1-2-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok2-1-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok2-2-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok3-1-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok3-2-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok4-1-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                  $htmlresz .= "frmvalidator.addValidation('parositasok4-2-".$kerdesarr[$i]."','dontselect=000','Nincs kiválasztás!');";
                }
                
                $htmlresz .= "</script>";
                
                echo $htmlresz;
          break;
        
        default:;
          break;
      }
        return true;
      
      
    }
      
    
    
    
    function UpdateVizsgazasDB($formvars){
      
      $query = "UPDATE ".$this->tablanev." SET tanulomegoldasa='".$formvars['megoldasok']."' WHERE vizsgaid=".$formvars['vizsgaid']." AND tanuloid=".$formvars['userid']; 
      
      
      if(!mysql_query( $query ,$this->kapcsolat))
      {
        $this->HandleDBError("Hiba az adatok frissítésekor(UPDATE)\nquery:$query");
        return false;
      }
      
      return true;
      
    }
    
    function DisplayTestResults()
    {
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return "";
      }
      
      //Csak a saját vizsgái jelennek meg!
      //1-SuperUser, 2-tanulo, 3-tanar
      if($_SESSION['type_of_user'] == 3){
        
        $qry = "Select id, aktualis, jelentkezesihatarido, vizsgaideje, cime, maxletszam, vizsgatipus, kerdesid from vizsgak where tanar=".$_SESSION['id_user']." ORDER BY id ASC";
        
        $result = mysql_query($qry,$this->kapcsolat);
        
        if(!$result || mysql_num_rows($result) <= 0)
        {
          $this->HandleError("Hiba a bejelentkezeskor. Hibas felhasznalonev, vagy jelszo");
          return "";
        }
        
        $htmlresz = "<TABLE id='table-design' style='width: 560px;'>";
        $htmlresz .= "<THEAD><TH>Id</TH><TH>Cím</TH><TH>Vizsga ideje</TH><TH>Max létszám</TH><TH>Vizsga típusa</TH></THEAD><TBODY>";
        while ($row = mysql_fetch_assoc($result)){
          $htmlresz .= "<TR><TD>".$row['id']."</TD>";
          $htmlresz .= "<TD>".$row['cime']."</TD>";
          //$htmlresz .= "<td>".$row['aktualis']."</td>";
          $htmlresz .= "<TD>".$row['vizsgaideje']."</TD>";
          $htmlresz .= "<TD>".$row['maxletszam']."</TD>";
          $htmlresz .= "<TD>". $this->VizsgatipusNumToString($row['vizsgatipus']) ."</TD></TR>";
          //$htmlresz .= "<td>".$row['kerdesid']."</td></tr>";
        }
        $htmlresz .= "</TBODY></TABLE>";
        
        return $htmlresz;
        
        //2-tanulo
      }else if($_SESSION['type_of_user'] == 2) {
        
        //ha tanulo, akkor kilistazom a vizsgakat, hozzafuzve a bejelentkezesi informaciokat is a "jelentkezett" oszlopba
        
        //Ez volt az első lekérdezés: SELECT vizsgak.*, IF((SELECT vizsgaid FROM vizsgarajelentkezesek WHERE((vizsgaid = vizsgak.id AND tanuloid = ".$_SESSION['id_user'].")) ) = vizsgak.id, 1, 0) AS jelentkezett FROM vizsgak ORDER BY id ASC

        //Ez meg a második: SELECT vizsgak. * , IF( (SELECT vizsgaid FROM vizsgarajelentkezesek WHERE ((vizsgaid = vizsgak.id AND tanuloid =8 )) ) = vizsgak.id, 1, 0 ) AS jelentkezett,
        //                  IF( ( SELECT tanulomegoldasa FROM vizsgarajelentkezesek WHERE ((vizsgaid = vizsgak.id AND tanuloid =8)) ) = "", 0, 1) AS vizsgazott FROM vizsgak
        //                  ORDER BY id ASC
        $qry = "SELECT vizsgak.*, IF((SELECT vizsgaid FROM vizsgarajelentkezesek WHERE((vizsgaid = vizsgak.id AND tanuloid = ".$_SESSION['id_user'].")) ) = vizsgak.id, 1, 0) AS jelentkezett, IF( COALESCE(( SELECT tanulomegoldasa FROM vizsgarajelentkezesek WHERE (vizsgaid = vizsgak.id AND tanuloid = ".$_SESSION['id_user'].") ),'') = '', 0, 1) AS vizsgazott FROM vizsgak ORDER BY id ASC";
        //SELECT vizsgak.*, IF((SELECT vizsgaid FROM vizsgarajelentkezesek WHERE((vizsgaid = vizsgak.id AND tanuloid = 8)) ) = vizsgak.id, 1, 0) AS jelentkezett, IF( ( SELECT tanulomegoldasa FROM vizsgarajelentkezesek WHERE ((vizsgaid = vizsgak.id AND tanuloid = 8)) ) = '', 0, 1) AS vizsgazott FROM vizsgak ORDER BY id ASC
        
        
        $result = mysql_query($qry,$this->kapcsolat);
        
        if(!$result || mysql_num_rows($result) <= 0)
        {
          $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
          return "";
        }
        
        $htmlresz = "<TABLE id='table-design' style='width: 560px;'>";
        $htmlresz .= "<THEAD><TH>Id</TH><TH>Cím</TH><TH>Vizsga ideje</TH><TH>Max létszám</TH><TH>Vizsga típusa</TH><TH>Jegy</TH><TH>Megjegyzés</TH></THEAD><TBODY>";
        while ($row = mysql_fetch_assoc($result)){
          
          $hatarido = $row['jelentkezesihatarido'];
          $now = date('Y-m-d H:i:s');
          $vizsgaidoPlusznegyOra = date('Y-m-d H:i:s', strtotime($row['vizsgaideje']) + 4*3600);
          
          $htmlresz .= "<TR><TD>".$row['id']."</TD>";
          
          //Ha a tanulo még nem tett vizsgát ebben a tesztben és a vizsgaido eljött és benne vagyunk a biztonság kedvéért 4 órásra vett időszeletben, akkor elkezdődhet a vizsga
          if ( ($now < $vizsgaidoPlusznegyOra) && ($now > $row['vizsgaideje'])){
            if ( ($row['vizsgazott'] == 0) && ($row['jelentkezett'] == 1) ){
              $htmlresz .= "<TD><form action='vizsgabetoltes.php' method='post' accept-charset='UTF-8'>";
              $htmlresz .= "<input type='hidden' name='submitted' value='".$row['id']."-".$_SESSION['id_user']."'/>";
              $htmlresz .= "<input type='submit' name='submit' value='".$row['cime']."'/></form></TD>";
            }elseif ( ($row['vizsgazott'] == 1) && ($row['jelentkezett'] == 1)) {
              $htmlresz .= "<TD><form action='vizsgavisszanezes.php' method='post' accept-charset='UTF-8'>";
              $htmlresz .= "<input type='hidden' name='submitted' value='".$row['vizsgatipus'].$row['id']."-".$_SESSION['id_user']."'/>";
              $htmlresz .= "<input type='submit' name='submit' value='[".$row['cime']."]'/></form></TD>";
            }else {
              $htmlresz .= "<TD>".$row['cime']."</TD>";
            }
          } else {
            if ($row['vizsgazott'] == 0){
              $htmlresz .= "<TD>".$row['cime']."</TD>";
            }else {
              $htmlresz .= "<TD><form action='vizsgavisszanezes.php' method='post' accept-charset='UTF-8'>";
              $htmlresz .= "<input type='hidden' name='submitted' value='".$row['vizsgatipus'].$row['id']."-".$_SESSION['id_user']."'/>";
              $htmlresz .= "<input type='submit' name='submit' value='[".$row['cime']."]'/></form></TD>";
            }
            
          }
          
          //$htmlresz .= "<TD>".$row['aktualis']."</TD>";
          $htmlresz .= "<TD>".$row['vizsgaideje']."</TD>";
          $htmlresz .= "<TD>".$row['maxletszam']."</TD>";
          $htmlresz .= "<TD>". $this->VizsgatipusNumToString($row['vizsgatipus']) ."</TD>";
          //$htmlresz .= "<TD>".$row['kerdesid']."</TD>";
          
          if ($row['jelentkezett'] == 0){

            if ($hatarido < $now){
              //Ha a jelentkezesi hatarido mar lejart, akkor nem lehet erre a vizsgara jelentkezni
              $jegy = $this->JegyLekerdezes($row['id']);
              $htmlresz .= "<TD>".$jegy."</TD>";
              $htmlresz .= "<TD>Nem lehet jelentkezni</TD>";
              
            }else {
              //Ha a value-nál 0 van, az JELENTKEZÉS jelent, ha 1, akkor az LEIRATKOZAST jelent a vizsgarol
              $jegy = $this->JegyLekerdezes($row['id']);
              $htmlresz .= "<TD>".$jegy."</TD>";
              $htmlresz .= "<TD><form action='loginhome.php' method='post' accept-charset='UTF-8'>";
              $htmlresz .= "<input type='hidden' name='submitted' value='0".$row['id']."-".$_SESSION['id_user']."'>";
              $htmlresz .= "<input type='submit' value='Jelentkezés'></form></TD></TR>";
            }
            
          }else if ($row['jelentkezett'] == 1) {
            
            if ($hatarido < $now){
              //Ha a jelentkezesi hatarido mar lejart, akkor nem lehet erre a vizsgarol leiratkozni
              $jegy = $this->JegyLekerdezes($row['id']);
              $htmlresz .= "<TD>".$jegy."</TD>";
              $htmlresz .= "<TD>Nem lehet leiratkozni</TD></TR>";
            }else {
              //Ha a value-nál 0 van, az JELENTKEZÉS jelent, ha 1, akkor az LEIRATKOZAST jelent a vizsgarol
              $jegy = $this->JegyLekerdezes($row['id']);
              $htmlresz .= "<TD>".$jegy."</TD>";
              $htmlresz .= "<TD><form action='loginhome.php' method='post' accept-charset='UTF-8'>";
              $htmlresz .= "<input type='hidden' name='submitted' value='1".$row['id']."-".$_SESSION['id_user']."'>";
              $htmlresz .= "<input type='submit' value='Leiratkozás'></form></TD></TR>";
            }
          }
          
        }
        $htmlresz .= "</TBODY></TABLE>";
        
        return $htmlresz;
        
      }else if($_SESSION['type_of_user'] == 1){
        //ha superuser
        
        $qry = "Select id, nev, email, felhasznalonev, felhasznalotipus, jelszo from users order by id asc";
        
        $result = mysql_query($qry,$this->kapcsolat);
        
        if(!$result || mysql_num_rows($result) <= 0)
        {
          $this->HandleError("Hiba a bejelentkezéskor. Hibás felhasználónév, vagy jelszó");
          return "";
        }
        
        $htmlresz = "<TABLE id='table-design' style='width: 560px;'>";
        $htmlresz .= "<THEAD><TH>Id</TH><TH>Név</TH><TH>E-mail cím</TH><TH>Felhasználó név</TH><TH>Felhasználó típus</TH></THEAD><TBODY>";
        while ($row = mysql_fetch_assoc($result)){
          
          $htmlresz .= "<form action=jogosultsagok.php method='post' accept-charset='UTF-8'>";
          $htmlresz .= "<input type='hidden' name='submitted' value='1'>";
          $htmlresz .= "<input type='hidden' name='userid' value='".$row['id']."'>";
          $htmlresz .= "<TR><TD><input type='submit' name='submit' value='".$row['id']."'></TD>";
          $htmlresz .= "</form>";
                    
          $htmlresz .= "<TD>".$row['nev']."</TD>";
          $htmlresz .= "<TD>".$row['email']."</TD>";
          $htmlresz .= "<TD>".$row['felhasznalonev']."</TD>";
          $htmlresz .= "<TD>".$row['felhasznalotipus']."</TD></TR>";
        }
        $htmlresz .= "</TBODY></TABLE>";
        
        return $htmlresz;
        
        
      }
      
      
      
    }
    
    function SuperuserTipusFrissitese(&$formvars){
      $formvars['userid'] = $_POST['userid'];
      $formvars['nev'] = $_POST['nev'];
      $formvars['email'] = $_POST['email'];
      $formvars['felhasznalonev'] = $_POST['felhasznalonev'];
      $formvars['felhasznalotipus'] = $_POST['felhasznalotipus'];
      
      if(!$this->DBLogin())
      {
        $this->HandleError("Adatbázisba bejelentkezés sikertelen!");
        return "";
      }
      //nev, email, felhasznalonev, felhasznalotipus
      $qry = "UPDATE users SET nev='".$formvars['nev']."', email='".$formvars['email']."', felhasznalonev='".$formvars['felhasznalonev']."',
              felhasznalotipus=".$formvars['felhasznalotipus']." WHERE id=".$formvars['userid'];
      
      $result = mysql_query($qry,$this->kapcsolat);
      
      echo "Sikeres adatmódosítás";
      
    }
    
    
    //-----Bal alsó sarokba kellenek a meghatározott felhasználóra jellemző eszközök
    function WhatKindOfUser()
    {
      //SuperUsernél - kell egy "Jogosultság" link bal oldalra, amiből ->byte(1)
      //Tanulónál kell "Vizsgázni", "Vizsgak   " link                  ->byte(2)
      //Tanárnál kell "Teszt készítés", "Eredmények", "KérdésBank"     ->byte(3)
      
      $this->CheckLoginInDB($felhasznalonev, $jelszo);
      
      switch ($_SESSION["type_of_user"])
      {
        case 1: ;//$htmlreszlet .= "<p><a href='jogosultsagok.php'>Jogosultságok</a></p>";
                break;
        case 2: //$htmlreszlet = "<p><a href='vizsgaeredmenyek.php'>Eredmények</a></p>";
                $htmlreszlet .= "<p><a href='loginhome.php'>Meghirdetett Vizsgák</a></p>";
                break;
        case 3: $htmlreszlet = "<p><a href='tesztvalaszto.php'>Teszt készítése</a></p>";
                $htmlreszlet .= "<p><a href='loginhome.php'>Vizsgák</a></p>";
                $htmlreszlet .= "<p><a href='kerdesbank.php'>KérdésBank</a></p>";                
                $htmlreszlet .= "<p><a href='torlendokerdesvalaszto.php'>KérdésBank-Törlés</a></p>";
                break;
      }
    
      return $htmlreszlet;
    
    }
    
    public static function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = altalanos::GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    
    public static function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }
    
    private static function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }
    
    public static function LogOut()
    {
        session_start();
        
        $sessionvar = altalanos::GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;
        
        unset($_SESSION[$sessionvar]);
        session_destroy();
    }
    
    public static function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Az e-mail üres!");
            return false;
        }
        
        $db = self::AlkalmiDBNyitas();
        
        $user_rec = array();
        if(false === $db->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === altalanos::SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    private static function AlkalmiDBNyitas(){
      $db = new AdatbazisKezelo();
      $db->InitDB(	/*hostname*/'127.0.0.1',
          /*username*/'fejesattila',
          /*password*/'monor123',
          /*database name*/'fejesattila',
          /*table name*/'felhasznalok');
      $db->SetDataTable("felhasznalok");
      return $db;
    }
    
    public static function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Az e-mail üres!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("A reset kód üres!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
        
        if(altalanos::GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Rossz reset kód!");
            return false;
        }
        
        $db = self::AlkalmiDBNyitas();
        
        $user_rec = array();
        if(!$db->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }
        
        $new_password = altalanos::ResetUserPasswordInDB($user_rec, $db);
        if(false === $new_password || empty($new_password))
        {
            $db->HandleError("Hiba az új jelszó frissítésekor");
            return false;
        }
        
        if(false == altalanos::SendNewPassword($user_rec,$new_password))
        {
            $db->HandleError("Hiba az új jelszó küldésénél");
            return false;
        }
        return true;
    }
    
    public static function ChangePassword()
    {
        if(!self::CheckLogin())
        {
            self::HandleError("Nincs bejelentkezve!");
            return false;
        }
        
        if(empty($_POST['oldpwd']))
        {
            self::HandleError("A régi jelszó üres!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            self::HandleError("Az új jelszó üres!");
            return false;
        }
        
        $db = self::AlkalmiDBNyitas();
        
        $user_rec = array();
        if(!$db->GetUserFromEmail(self::UserEmail(),$user_rec))
        {
            return false;
        }
        
        $jelszo = trim($_POST['oldpwd']);
        
        if($user_rec['pass'] != md5($jelszo))
        {
            self::HandleError("Nem találom a régi jelszót!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);
        
        if(!$db->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }
    
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }    
    
    public static function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }
    
    public static function RedirectToURL($url)
    {
      header("Location: ".$url);
      exit;
    }
    
    private static function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.self::$rand_key);
    }
    
    public static function GetErrorMessage()
    {
        if(empty(self::$error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities(self::$error_message));
        return $errormsg;
    }    
    //-------Private Helper functions-----------
    
    private static function HandleError($err)
    {
        self::$error_message .= $err."\r\n";
    }
    
    private static function GetFromAddress()
    {
        /*if(!empty($this->from_address))
        {
            return $this->from_address;
        }*/

        $host = $_SERVER['SERVER_NAME'];

        $from ="fejesattila@mail.ininet.hu";
        return $from;
    } 
    
    public static function GetLoginSessionVar()
    {
        $retvar   = md5(self::$rand_key);
        $retvar   = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    
    private static function ResetUserPasswordInDB($user_rec, AdatbazisKezelo $db)
    {
        $new_password = substr(md5(uniqid()),0,10);
        
        if(false == $db->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }
    
    function SendUserWelcomeEmail(&$user_rec)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($user_rec['email'],$user_rec['nev']);
        
        $mailer->Subject = "Üdvözlet a(z)".$this->sitename." oldalon";

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body ="Hello ".$user_rec['nev']."\r\n\r\n".
        "Üdvözlet! A regisztrációd a(z) ".$this->sitename." oldalon kész!\r\n".
        "\r\n".
        "Üdvözlettel,\r\n".
        "Admin\r\n".
        $this->admin_email;

        if(!$mailer->Send())
        {
            $this->HandleError("Sikertelen üdvözlő email küldés.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Sikeres regisztráció: ".$user_rec['nev'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Új felhasználó regisztrált a(z) ".$this->sitename." oldalon\r\n".
        "név: ".$user_rec['nev']."\r\n".
        "Email: ".$user_rec['email']."\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    private static function GetResetPasswordCode($email)
    {
      /*Itt csak példányosítani kell, mert a konstruktorban már létrehozza a sitename-et és nekem csak ez kell*/
      $db = new AdatbazisKezelo();
      return substr(md5($email.$db->getSiteName().self::$rand_key),0,10);
    }
    
    private static function SendResetPasswordLink($user_rec)
    {
        $email = "csapo.janos@gmail.com";/*$user_rec['email'];*/
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        /*$mailer->AddAddress($email,$user_rec['fullname']);*/
        $mailer->AddAddress($email);
        
        $mailer->Subject = "Új jelszót kértél a Szörnyes-Társas oldalon";

        $mailer->From = "fejesattila@ininet.hu";/*altalanos::GetFromAddress();*/
        
        
        $link = altalanos::GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode(altalanos::GetResetPasswordCode($email));

        
        $mailer->Body ="Hello ".$user_rec['fullname']."\r\n\r\n".
        "Egy új jelszó kérelem érkezett a Szörnyes-Társas oldalról\r\n".
        "Kérlek kattints az alábbi linkre a végrehajtásához: \r\n".$link."\r\n".
        "Üdvözlettel,\r\n".
        "Admin\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    private static function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($email,$user_rec['fullname']);
        
        $mailer->Subject = "Az új jelszavad a Szornyes-Tarsas oldalhoz";

        $mailer->From = altalanos::GetFromAddress();
        
        $mailer->Body ="Hello ".$user_rec['fullname']."\r\n\r\n".
        "Új jelszó kérelmezésed sikeres. ".
        "Itt vannak az új belenetkezési információid:\r\n".
        "Felhasználónév:".$user_rec['username']."\r\n".
        "jelszo: ".$new_password."\r\n".
        "\r\n".
        "Be is jelentkezhetsz azonnal: ".altalanos::GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Üdvözlettel,\r\n".
        "Admin\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    public static function RegisztracioValidalas()
    {
      
        //Ez itten egy rejtett input mező.
        if(!empty($_POST[self::GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            self::HandleError("Automated submission prevention: case 2 failed");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("teljesnev","req","Kérlek töltsd ki a nevet");
        $validator->addValidation("email","email","Az Email-nek üresnek kell lennie!");
        $validator->addValidation("email","req","Kérlek töltsd ki az Email-t");
        $validator->addValidation("username","req","Kérlek töltsd ki a felhasználónevet");
        $validator->addValidation("jelszo","req","Kérlek töltsd ki a jelszót");

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            echo $error;exit;
            self::HandleError($error);
            return false;
        }        
        return true;
    }
    
    public static function RegisztraciosAdatokOsszegyujtese(&$formvars)
    {
        $formvars['teljesnev'] = self::Sanitize($_POST['teljesnev']);
        $formvars['email'] = self::Sanitize($_POST['email']);
        $formvars['username'] = self::Sanitize($_POST['username']);
        $formvars['jelszo'] = self::Sanitize($_POST['jelszo']);
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($formvars['email'],$formvars['nev']);
        
        $mailer->Subject = "Regisztráltál a(z) ".$this->sitename." oldalon";

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body ="Hello ".$formvars['nev']."\r\n\r\n".
        "Köszönjük, hogy regisztráltál a(z) ".$this->sitename." oldalunkon\r\nVedd fel a kapcsolatot az Adminnal, hogy beállítsa a felhasználód típusát!".
        "\r\n".
        "Üdvözlettel,\r\n".
        "Admin\r\n".
        $this->admin_email;

        if(!$mailer->Send())
        {
            $this->HandleError("Sikertelen regisztrálciós email küldés.");
            return false;
        }
        return true;
    }
    
    private static function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'utf-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Új regisztráció: ".$formvars['nev'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Egy új felhasználó regisztrált ".$this->sitename."\r\n".
        "Név: ".$formvars['nev']."\r\n".
        "Email cím: ".$formvars['email']."\r\n".
        "Felhasználónév: ".$formvars['username'];
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    public static function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = mysql_real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 /*
     Az alább elhelyezkedő kód eltávolít mindenféle fenyegetést az adatbázisba tartó adatokról.
	 Megelőzi az "email injection" elnevezésű rosszindulatú admin jogok megszerzését,
	 valamint más hacker támadásoktól is védelmet nyújt.
     ha a $remove_nl true értéket vesz fel. Az új sor karakterek is eltávolítottak a bemenő adatokról.
    */
    private static function Sanitize($str,$remove_nl=true)
    {
        $str = self::StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    
    private static function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }    
}
?>