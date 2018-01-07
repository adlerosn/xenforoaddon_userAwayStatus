<?php
class userAwayStatus_Template_Helper_Core extends XenForo_Template_Helper_Core
{
	public static $overridenHelperCallbacks = array(
		'avatarhtml' => array(null, array('userAwayStatus_Template_Helper_Core', 'helperAvatarHtml')),
		'userblurb'  => array(null, array('userAwayStatus_Template_Helper_Core', 'helperUserBlurb')),
		'userbanner' => array(null, array('userAwayStatus_Template_Helper_Core', 'helperUserBanner')),
		); /* First position of inner arrays (null) are going to be replaced in runtime */
		
		
	public static function callOldHelper($helper, array $args)
	{
		$helper = strtolower(strval($helper));
		// If uninitialized or reference lost...
		if (!isset( self::$overridenHelperCallbacks[$helper]) ||
			!isset( self::$overridenHelperCallbacks[$helper][0]) ||
			is_null(self::$overridenHelperCallbacks[$helper][0]))
		{
			// ...ignore because other add-on messed up with this one.
			return '';
		}
		return call_user_func_array(self::$overridenHelperCallbacks[$helper][0], $args);
	}
	
	protected static function getUserLastActivity($user){
		$la = 0;
		if(isset($user['effective_last_activity']))
			$la = $user['effective_last_activity'];
		else{
			$uam = XenForo_Model::create('userAwayStatus_UserActivity');
			$data = $uam->getLastActivity($user['user_id']);
			if(isset($data['effective_last_activity']))
				$la = max([$data['last_activity'],$user['last_activity'],$data['effective_last_activity']]);
			else
				$la = max([$data['last_activity'],$user['last_activity']]);
		}
		return $la;
	}
	
	protected static function isUserAwayWhitelist($userid){
		return in_array(strval($userid),explode(',',XenForo_Application::getOptions()->userAwayFromSiteWhitelist));
	}
	
	protected static function isUserAwayBlacklist($userid){
		return in_array(strval($userid),explode(',',XenForo_Application::getOptions()->userAwayFromSiteBlacklist));
	}
	
	public static function helperAvatarHtml(array $user, $img, array $attributes = array(), $content = ''){
		//First we call the old helper
		$res = self::callOldHelper('avatarhtml',func_get_args());
		//Then we adjust its output
		$uafstdl = 3600*24*XenForo_Application::getOptions()->userAwayFromSiteTimeDayLimit;
		$la = intval(self::getUserLastActivity($user));
		if((intval($la)!=0 && (time()-$la)>$uafstdl && !static::isUserAwayWhitelist($user['user_id'])) || static::isUserAwayBlacklist($user['user_id']) || (array_key_exists('is_banned',$user) && $user['is_banned'])){
			$res = userAwayStatus_strTools::str_findFirst_appendAfter($res,'class="','userAwayFromSite ');
		}
		//Finally we return it
		return $res;
	}

	public static function helperUserBlurb(array $user, $includeUserTitle = true){
		//First we call the old helper
		$res = self::callOldHelper('userblurb',func_get_args());
		//Then we adjust its output
		$uafstdl = 3600*24*XenForo_Application::getOptions()->userAwayFromSiteTimeDayLimit;
		$ubtt = XenForo_Application::getOptions()->userAwayFromSiteBlurb;
		$la = intval(self::getUserLastActivity($user));
		if((intval($la)!=0 && (time()-$la)>$uafstdl && strlen($ubtt) && !static::isUserAwayWhitelist($user['user_id'])) || static::isUserAwayBlacklist($user['user_id']) || (array_key_exists('is_banned',$user) && $user['is_banned'])){
			$res = implode(', ', array_merge(explode(', ',$res),[$ubtt]));
		}
		//Finally we return it
		return $res;
	}

	public static function helperUserBanner($user, $extraClass = '', $disableStacking = false){
		//First we call the old helper
		$res = self::callOldHelper('userbanner',func_get_args());
		//Then we adjust its output
		$uafstdl = 3600*24*XenForo_Application::getOptions()->userAwayFromSiteTimeDayLimit;
		$la = intval(self::getUserLastActivity($user));
		$bannerclass = XenForo_Application::getOptions()->userAwayFromSiteBannerClass['class'];
		$bannertext = XenForo_Application::getOptions()->userAwayFromSiteBannerText;
		if((intval($la)!=0 && (time()-$la)>$uafstdl && strlen($bannertext) && !static::isUserAwayWhitelist($user['user_id'])) || static::isUserAwayBlacklist($user['user_id']) || (array_key_exists('is_banned',$user) && $user['is_banned'])){
			$res.='<em class="' . $bannerclass . ' ' . $extraClass . '" itemprop="title"><span class="before"></span><strong>' . $bannertext . '</strong><span class="after"></span></em>';
		}
		//Finally we return it
		return $res;
	}
}
