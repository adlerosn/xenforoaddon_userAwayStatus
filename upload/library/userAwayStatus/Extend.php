<?php

class userAwayStatus_Extend {
	public static function getExtensions(){
		return [
			['XenForo_ControllerPublic_Forum', 'userAwayStatus_ControllerPublic_Forum'],
		];
	}
	public static function callback($class, array &$extend){
		$xtds = static::getExtensions();
		foreach($xtds as $xtd){
			$baseClass = $xtd[0];
			$toExtend = $xtd[1];
			if($class==$baseClass && !in_array($toExtend, $extend)){
				$extend[]=$toExtend;
			}
		}
	}
}
