<?php
header("Content-Type: text/html; charset=UTF-8");
class targy
{
  private $ar;
  private $sebzesmin;
  private $sebzesmax;
  private $megnevezes;
  private $hely;
  private $kategoria;
  private $hatashelye;
  private $modosito;
  private $szint;
  private $toltet;
  
  public function getAr (){ return $this->ar; }
  public function setAr ($ar){ $this->ar = $ar; }
  
  public function getSebzesmin (){ return $this->sebzesmin; }
  public function setSebzesmin ($sebzesmin){ $this->sebzesmin = $sebzesmin; }
  
  public function getSebzesmax (){ return $this->sebzesmax; }
  public function setSebzesmax ($sebzesmax){ $this->sebzesmax = $sebzesmax; }
  
  public function getMegnevezes (){ return $this->megnevezes; }
  public function setMegnevezes ($megnevezes){ $this->megnevezes = $megnevezes;}
  
  public function getHely (){ return $this->hely; }
  public function setHely ($hely){ $this->hely = $hely; }
  
  public function getKategoria (){ return $this->kategoria; }
  public function setKategoria ($kategoria){ $this->kategoria = $kategoria; }
  
  public function getHatashelye (){ return $this->hatashelye; }
  public function setHatashelye ($hatashelye){ $this->hatashelye = $hatashelye; }
  
  public function getModosito (){ return $this->modosito; }
  public function setModosito ($modosito){ $this->modosito = $modosito; }
  
  public function getSzint (){ return $this->szint; }
  public function setSzint ($szint){ $this->szint = $szint; }
  
  public function getToltet (){ return $this->toltet; }
  public function setToltet ($toltet){ $this->toltet = $toltet; }

  

  public function Create( int $ar_,       /*a tárgy ára*/ 
                          int $sebzesmin_,/*a tárgy minimum sebzése*/
                          int $sebzesmax_,/*a tárgy maximum sebzése*/
                          string $megnevezes,/*a tárgy neve pl.: kés, kasza*/
                          int $hely_,/*melyik testrészen viselhető a tárgy*/
                          int $kategoria_,/*a tárgy milyen fajtájú, pluszos, munuszos...*/
                          int $hatashelye_,/*a tárgy hatása hol jelentkezik*/
                          int $modosito_,/*mekkora a modositas merteke*/
                          int $szint_,
                          int $toltet_/*hanyszor lehet használni a tárgyat*/ ){
    
    $this->ar = $ar_;
    $this->sebzesmin = $sebzesmin_;
    $this->sebzesmax = $sebzesmax_;
    $this->megnevezes = $megnevezes;
    $this->hely = $hely_;
    $this->kategoria = $kategoria_;
    $this->hatashelye = $hatashelye_;
    $this->modosito = $modosito_;
    $this->szint = $szint_;
    $this->toltet = $toltet_;
  }
  
  
  
  
  
  

}

?>