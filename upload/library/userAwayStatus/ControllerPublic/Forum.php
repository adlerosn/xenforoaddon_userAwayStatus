<?php

class userAwayStatus_ControllerPublic_Forum extends XFCP_userAwayStatus_ControllerPublic_Forum{
	public function actionIndex(){
		$r = parent::actionIndex();
		if($r instanceof XenForo_ControllerResponse_View){
			if(array_key_exists('boardTotals',$r->params)){
				$usersNotAway = [];
				$db = XenForo_Application::getDb();
				$xfOpt = XenForo_Application::getOptions();
				foreach(explode(',',$xfOpt->userAwayFromSiteTimesStatistics) as $days){
					$days = intval($days);
					if($days<=0) continue;
					$minTime = time()-3600*24*$days;
					$q = "select count(user_id) as usersNotAway from (select user.user_id, IF (session_activity.view_date IS NULL, user.last_activity, session_activity.view_date) AS effective_last_activity from xf_user as user LEFT JOIN xf_session_activity AS session_activity ON (session_activity.user_id = user.user_id AND session_activity.unique_key = user.user_id)) as sq1 where effective_last_activity >= ? ;";
					$count = $db->fetchRow($q,[$minTime])['usersNotAway'];
					$usersNotAway[]=[
						'days'=>$days,
						'count'=>$count
					];
				}
				$usersNotAway = array_reverse($usersNotAway);
				$r->params['boardTotals']['usersNotAway']=$usersNotAway;
			}
		}
		return $r;
	}
}
