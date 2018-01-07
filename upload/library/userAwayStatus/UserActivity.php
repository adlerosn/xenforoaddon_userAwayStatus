<?php

class userAwayStatus_UserActivity extends XenForo_Model {
	public function getLastActivity_uncached($uid){
		return $this->_getDb()->fetchRow('
			SELECT
				user.user_id,
				user.last_activity,
				IF (
					session_activity.view_date
						IS NULL,
					user.last_activity,
					session_activity.view_date
				)
				AS
					effective_last_activity
			FROM
				xf_user
					AS user
				LEFT JOIN xf_session_activity
					AS session_activity
					ON
						(
						session_activity.user_id = user.user_id
							AND
						session_activity.unique_key = user.user_id
						)
			WHERE user.user_id = ?
		',[$uid]);
	}
	protected static $_lastActivityCache = [];
	public function getLastActivity($uid){
		if(!array_key_exists($uid,static::$_lastActivityCache)){
			static::$_lastActivityCache[$uid] = $this->getLastActivity_uncached($uid);
		}
		return static::$_lastActivityCache[$uid];
	}
}

