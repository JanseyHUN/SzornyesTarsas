<?php
class konst
{
  const helyFEJ          = 0;
  const helyVALL         = 1;
  const helyNYAK         = 2;
  const helyFELSOTEST    = 3;
  const helyKARBAL       = 4;
  const helyKARJOBB      = 5;
  const helyLAB          = 6;
  const helyCSUKLOBAL    = 7;
  const helyCSUKLOJOBB   = 8;
  const helyUJBAL1       = 9;
  const helyUJBAL2       = 10;
  const helyUJJOBB1      = 11;
  const helyUJJOBB2      = 12;
  
  const katPLUSZOS       = 0;
  const katMINUSZOS      = 1;
  const katLEHETOSEG     = 2;
  
  const hatTAMADAS       = 0;
  const hatSEBZES        = 1;
  const hatMOZGAS        = 2;
  const hatSAJATDOBAS    = 3;
  const hatSZORNYDOBAS   = 4;
  const hatMENEKULES     = 5;

  
  public static function convHatas($hatas){
    switch ($hatas) {
      case 0: $res = "Támadás";
        break;
      case 1: $res = "Sebzés";
        break;
      case 2: $res = "Mozgás";
        break;
      case 3: $res = "Saját dobás";
        break;
      case 4: $res = "Szörny dobás";
        break;
      case 5: $res = "Menekülés";
        break;
      
      default: $res = "Valami nincs rendben";        
        break;
    }
    return $res;
  }
  
  public static function convKategoria($kategoria){
    switch ($kategoria) {
      case 0: $res = "Pluszos";
        break;
      case 1: $res = "Minuszos";
        break;
      case 2: $res = "Lehetőség szerinti";
        break;
      
      default: "Valami nincs rendben";
      break;
    }
    return $res;
  }
  
  public static function convHely($hely){
    switch ($hely) {
      case 0: $res = "Fej";
        break;
      case 1: $res = "Váll";
        break;
      case 2: $res = "Nyak";
        break;
      case 3: $res = "Felsőtest";
        break;
      case 4: $res = "Bal kar";
        break;
      case 5: $res = "Jobb kar";
        break;
      case 6: $res = "Láb";
        break;
      case 7: $res = "Bal csukló";
        break;
      case 8: $res = "Jobb csukló";
        break;
      case 0: $res = "Bal kéz egyik ujj";
        break;
      case 0: $res = "Bal kéz másik ujj";
        break;
      case 0: $res = "Jobb kéz egyik ujj";
        break;
      case 0: $res = "Jobb kéz másik ujj";
        break;
      
      default: $res = "Valami gond van";
        break;
    }
    return $res;
  }
  
}

?>