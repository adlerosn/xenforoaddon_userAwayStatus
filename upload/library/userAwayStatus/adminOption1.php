<?php

class userAwayStatus_adminOption1 {
	public static function render_AdminCP_CustomLinksAdder(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit){
		$txt = XenForo_Application::getOptions()->userAwayFromSiteBannerText;
		$c = $preparedOption['option_value'];
		if(!isset($c['class'])) $c = unserialize($preparedOption['default_value']);
		$c = $c['class'];
		$ps = array(
			'userBanner bannerHidden',
			'userBanner bannerPrimary',
			'userBanner bannerSecondary',
			'userBanner bannerRed',
			'userBanner bannerGreen',
			'userBanner bannerOlive',
			'userBanner bannerLightGreen',
			'userBanner bannerBlue',
			'userBanner bannerRoyalBlue',
			'userBanner bannerSkyBlue',
			'userBanner bannerGray',
			'userBanner bannerSilver',
			'userBanner bannerYellow',
			'userBanner bannerOrange',
		);
		$s=[];
		$other = true;
		foreach($ps as $v){
			$s[]=['class'=>$v,'selected'=>$v==$c];
			$other = (($other)&&(!($v==$c)));
		}
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));

		return $view->createTemplateObject('option_user_away_status', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $preparedOption['formatParams'],
			'editLink' => $editLink,
			
			'current'=>$c,
			'bannerText'=>$txt,
			'displayStyles' => $s,
			'displayStylesOther' => $other,
		));
	}
	
	public static function verifier_AdminCP_CustomLinksAdder(array &$input, XenForo_DataWriter $dw, $fieldName){
		$output = [];
		
		if(isset($input['radio']) && !is_null($input['radio']) && strlen($input['radio'])>0){
			$output['class']=strval($input['radio']);
		}else if(isset($input['text']) && !is_null($input['text']) && strlen(strval($input['text']))>0){
			$output['class']=strval($input['text']);
		};
		
		if(!isset($output['class'])){
			$output['class']='userBanner bannerHidden';
		}
		
		//die(print_r([$input,$output],true));
		
		$input = $output;

		return true;
	}
}
