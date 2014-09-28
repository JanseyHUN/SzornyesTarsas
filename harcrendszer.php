<?php

class harcrendszer {

	private $Jatekos; 
	private $SzornyekSor = array();
	private $SzornySzintMinusz = 0;
	
	public function setSzornyek($SzornyekArr_){
		$szao = new ArrayObject($SzornyekArr_);
		$this->SzornyekSor = $szao->getArrayCopy();
	} 
	
	public function setJatekos(jatekos $Jatekos_){
	  
	  if ($Jatekos_ != null){
	    $this->Jatekos = $Jatekos_;
	  }
	}
		
	public function Menekul(){
		
		$this->Jatekos = null;
		unset($this->SzornyekSor);
	}
	
	
	private function SorbolKiveszSzornyet($index){
		unset($this->SzornyekSor[$index]);
	}
	
	private function CsataKimeneteleKiiratas(){
	  if (count($this->SzornyekSor) != 0){
	    echo "<br/><br/> VERESÉG!<br/>"; 
	  } else {
	    echo "<br/><br/> GYŐZELEM!<br/>";
	  }
	  
	  echo "<br/>Játékos szintje: ".$this->Jatekos->getSzint()."<br/>";
	  echo count($this->SzornyekSor)." maradt még!<br/>";
	}
	
	public function TamadasOsszes(){
	  
	  while ( (count($this->SzornyekSor) > 0) && ($this->Jatekos->getSzint() > 0) ){
	    $this->TamadasKoronkent();
	  }
	  
	  $this->CsataKimeneteleKiiratas();
	  
	}
	
	public function TamadasKoronkent(){
		
		/*Szorny
		 * [0] - szint
		 * [1] - sebzesMin
		 * [2] - sebzesMax
		 * [3] - szornyfajta megnevezes
		 * */
		$SzornySebzes = 0;
		
		foreach ($this->SzornyekSor as $Aktualisszorny) {
		  
		  while ( ($Aktualisszorny[0] != 0) && ($this->Jatekos->getSzint() != 0) ){
		    
		  	echo "<br/><br/>A Szörny(".$Aktualisszorny[3].")<br/>";
		    echo "-------------------<br/>";
		    echo "szintje: ".$Aktualisszorny[0]."<br/>";
		    echo "sebzése: ".$Aktualisszorny[1]."-".$Aktualisszorny[2]."<br/>";
		    
		    $SzornySebzes = rand($Aktualisszorny[1],$Aktualisszorny[2]);
		    
		    $JatekosSebzes = $this->Jatekos->sebzes();
		    if ($JatekosSebzes >= $SzornySebzes) {
		      $Aktualisszorny[0] -= 1;
		    }else {
		      $this->Jatekos->sebzodik(1);
		    }
		    
		    
		    if ($Aktualisszorny[0] == 0){
		    	echo "<br/>A ".$Aktualisszorny[3]." kikerült a sorból<br/>";
		    
		    	$index = array_search($Aktualisszorny, $this->SzornyekSor);
		    	unset($this->SzornyekSor[$index]);
		    	$UjszornySor = array_values($this->SzornyekSor);
		    	unset($this->SzornyekSor);
		    	$szao = new ArrayObject($UjszornySor);
		    	$this->SzornyekSor = $szao->getArrayCopy();
		    
		    	continue;
		    }
		    
		    
		    /* ezek minimális kiiratások, debug miatt lehet alkalmazni*/
		    echo "<br/>Játékos sebzése: ".$JatekosSebzes."<br/>";
		    echo "Szörny sebzése: ".$SzornySebzes."<br/>";
		    $this->AktualisAllapotKiiratasa();		    
		  }
		  
		}
	}
	
	public function AktualisAllapotKiiratasa(){
	  $this->Jatekos->Kiiratas();
	}
	
}

?>