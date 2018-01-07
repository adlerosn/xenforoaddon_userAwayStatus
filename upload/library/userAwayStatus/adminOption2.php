<?php

class userAwayStatus_adminOption2 {
	public static function fulltrim($totrim){
		$os = 0;
		while($os != strlen($totrim)){
			$os = strlen($totrim);
			$totrim = trim($totrim);
		}
		return $totrim;
	}
	public static function verifier_AdminCP_CustomLinksAdder(&$input, XenForo_DataWriter $dw, $fieldName){
		$output = [];
		
		foreach(explode(',',$input) as $uid){
			$uid = self::fulltrim($uid);
			$i = intval($uid);
			if($uid>0 && !in_array(strval($i),$output))
				$output[]=strval($i);
		}
		
		sort($output);
		
		$input = implode(',',$output);
		
		return true;
	}
}
