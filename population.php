<?php

require_once 'citizen.php';

class Population{
	
	const MAX_POPULATION = 10000;
	private $population = array();
	private $bestCitizens = array();
	private $objective = '';
	
	public function __construct(){
		$this->init();
	}
	
	private function init(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$this->population[$x] = new Citizen();
		}
		
		$this->bestCitizens[0] = new Citizen();
    $this->bestCitizens[0]->setFitness(1000);
	}
	
	public function calculateFitness(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$this->population[$x]->calculateFitness($this->objective);
		}
	} 
	
	public function setObjective($objective = NULL){
		try{
			if(is_null($objective)){
				throw new Exception('Error, no se ha especificado objetivo');
			}
			$this->objective = $objective;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function startUp(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$this->population[$x]->setData($this->randomData(strlen($this->objective)));
		}
	}
	
	private function randomData($length){
		$temp = '';
		for($x=0;$x<$length;$x++){
			$temp .= chr(mt_rand(32, 126));
		}
		return $temp;
	}
	
	private function cmp($a, $b){
		$a = $a->getFitness();
		$b = $b->getFitness();
		
	    if ($a == $b) {
	        return 0;
	    }
	    return ($a < $b) ? -1 : 1;
	}
	
	public function assignBestCitizens(){
		usort($this->population, array($this,"cmp"));
		
	  $this->bestCitizens[0]->setData($this->population[0]->getData());
  	$this->bestCitizens[0]->setFitness($this->population[0]->getFitness());
	}
	
	public function getBestCitizens(){
		return $this->bestCitizens;
	}
	
	public function reproduce(){
	  $best = $this->population[0]->getData();
		for($x=1; $x<self::MAX_POPULATION; $x++){
		  $temp = $this->population[$x]->getData();
      for($y=0;$y<strlen($this->objective);$y++){
        if(mt_rand(0,1)){
          $temp[$y] = $best[$y];
        }
      }
      
  		$this->population[$x]->setData($temp);
		}
	}
	
	public function mutate(){
		for($x=0; $x<self::MAX_POPULATION; $x++){
			$data = $this->population[$x]->getData();
			for($y=0;$y<strlen($this->objective);$y++){
			  if(mt_rand()%100<4){
			    $data[$y] = chr(mt_rand(32, 126));
			  }
			}
			
			$this->population[$x]->setData($data);
		}
	}
}

?>