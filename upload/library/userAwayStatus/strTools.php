<?php

class userAwayStatus_strTools {
	public static function str_contains($haystack,$needle){
		return (strpos($haystack, $needle) !== false);
	}
	
	public static function str_findFirst_appendAfter($haystack,$find,$append){
		if(!is_array($find)){
			$find = array($find);
		}
		foreach($find as $search){
			if(self::str_contains($haystack,$search)){
				$i = strpos($haystack, $search) + strlen($search);
				$haystack = substr($haystack,0,$i).$append.substr($haystack,$i);
			}
		}
		return $haystack;
	}
}
