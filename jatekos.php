<?php

class jatekos{

  public function __construct($nev_){
    $this->nev = $nev_;
  }

  private $sebzesMin = 2;
  private $sebzesMax = 9;
  private $szint = 5;
  private $nev;

  public function sebzes(){
    return rand($this->sebzesMin, $this->sebzesMax);
  }

  public function sebzodik($sebzes){
    $this->szint -= $sebzes;
  }

  public function getNev(){
    return $this->nev;
  }
  
  public function getSzint(){
    return $this->szint;
  }
  
  public function Kiiratas(){
    
    echo "<br/>Játékos<br/>";
    echo "-------<br/>";
    echo "Neve: ".$this->nev."<br/>";
    echo "sebzése: ".$this->sebzesMin."-".$this->sebzesMax."<br/>";
    echo "szintje: ".$this->szint;
  }

}

?>