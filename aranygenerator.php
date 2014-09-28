<? 
		function getArany($szint,$bonus) { /*szint és bónusz paraméterek átvétele */
			$max=$szint*10*$bonus;
			$min=5-(10-$szint);/* itt az alacsony szintű szörnyek könnyen minuszba csapnak igy alacsony szintűekért ritkábban jár arany*/
			$szamol_getArany=rand($min,$max);
			if ($szamol_getArany<=0) {
				$szamol_getArany=0;/*minusz arany azért ne legyen*/
				}
			return $szamol_getArany;
			}
	
?>