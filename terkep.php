<?php header("Content-Type: text/html; charset=UTF-8");$m = new Main();
$m->TerkepKeszites();$m->TerkepKinyomtatasa();
class Main{  private $oszlop = 0;
  private $sor = 0;
  private $terkep = array();
      private function VeletlenElemGen(){    return rand(0, 10);  }
     
  function TerkepKeszites() {    /*ilyen tag-ek közé téve a komment működik*/    for ($i = 0; $i < 10; $i++) {      for ($j = 0; $j < 10; $j++) {        $this->terkep[$i][$j] = $this->VeletlenElemGen();      }    }
  }    function TerkepKinyomtatasa(){    for ($i = 0; $i < 10; $i++) {
      for ($j = 0; $j < 10; $j++) {
        echo "Térkép $i sor, $j oszlop: ".$this->terkep[$i][$j]."<BR/>";
      }
    }  }  }?>