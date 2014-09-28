<?php
header("Content-Type: text/html; charset=UTF-8");

class Szornyek
{
  private $SzornyekArr              = array();
  private $ElsoszintuSzornyek       = array("Patkány","Egér");
  private $MasodikszintuSzornyek    = array("Márges pók","Gyilkos darázs","Dühös kutya", "Kígyó");
  private $HarmadikszintuSzornyek   = array("Vaddisznó");
  private $NegyedikszintuSzornyek   = array("Vadmacska","Farkas","Sas");
  private $OtodikszintuSzornyek     = array("Jeges medve","Grizli medve","Oroszlán","Krokodil","Kardfogú tigris");
  
  private function SzintGen($darabszam){
    $szintek = array();
    for ($i = 0; $i < $darabszam; $i++) {
      $szintek[$i] = rand(1,5);
    }
    return $szintek;
  }
  
  private function SzornyFajtaSzintSzerint($szint){
    switch ($szint) {
      case 1: $ValasztottFajta = rand(0,1);
              return $this->ElsoszintuSzornyek[$ValasztottFajta];
        break;
      case 2: $ValasztottFajta = rand(0,3);
              return $this->MasodikszintuSzornyek[$ValasztottFajta];
        break;
      case 3: $ValasztottFajta = 0;
              return $this->HarmadikszintuSzornyek[$ValasztottFajta];
        break;
      case 4: $ValasztottFajta = rand(0,2);
              return $this->NegyedikszintuSzornyek[$ValasztottFajta];
        break;
      case 5: $ValasztottFajta = rand(0,4);
              return $this->OtodikszintuSzornyek[$ValasztottFajta];
        break;
      
      default: return "Diablo";
        break;
    }
    
  }
  
  private function SzornyGen($szintek){
    
    $szornydarab = count($szintek);
    for ($i = 0; $i < $szornydarab; $i++) {
      
      switch ($szintek[$i]) {
        case 1:  $tamadas[0] = 2; $tamadas[1] = 3; 
          break;
        case 2:  $tamadas[0] = 2; $tamadas[1] = 4;
          break;
        case 3:  $tamadas[0] = 2; $tamadas[1] = 5;
          break;
        case 4:  $tamadas[0] = 3; $tamadas[1] = 6;
          break;
        case 5: $tamadas[0] = 4; $tamadas[1] = 7;
          break;
        
        default: $tamadas[0] = 0; $tamadas[1] = 0;
          break;
      }
      
      $this->SzornyekArr[$i][0] = $szintek[$i];
      $this->SzornyekArr[$i][1] = $tamadas[0];
      $this->SzornyekArr[$i][2] = $tamadas[1];
      $this->SzornyekArr[$i][3] = $this->SzornyFajtaSzintSzerint($szintek[$i]);
    }
  }
  
  public function SzornyekKiiratas(){
    $darab = 1;
    echo "<br/><br/>Szörnyek<br/>";
    echo "--------<br/>";
    foreach ($this->SzornyekArr as $szorny) {
      echo "$darab. Szörny (Fajta/szint/támadás): ".$szorny[3]."/".$szorny[0]."/".$szorny[1]."-".$szorny[2];
      echo "<BR/>";
      $darab++;
    }  
  }
  
  public function SzornyGenerator($darabszam) {
    $szintek = $this->SzintGen($darabszam);
    $this->SzornyGen($szintek);
  }
  
  public function getSzornyek(){
  	return $this->SzornyekArr;
  }
  
  
}

?>