<?php
function _m_index(){
	global $userinfo,
	$COOKIES_LIVE_TIME,$COOKIES_URL_ROOT, $COOKIES_DOMAIN,$SESSION_KEY_ID,
	$save_join,$client,$sk;
	
	if(GSet::v('autocall')) {
		if(isset($_SESSION['invited']) && $_SESSION['invited']>0) {
			unset($_SESSION['invited']);
			header('Location: '.SITE.GSet::v('autocall_wait_page'));
			exit(0);
		}

		if(isset($_REQUEST['kicked'])){
			//if autocall enabled, remove conf info from cookies
			setcookie("conference-id", '', time()-3600, $COOKIES_URL_ROOT, $COOKIES_DOMAIN);
			setcookie("conference-pass", '', time()-3600, $COOKIES_URL_ROOT, $COOKIES_DOMAIN);
		}
	}
	//saas redirection to ext url
	$confInfo = $client->_('getConferenceInfo',array($sk, $_SESSION['loggedinconf']));
	if(GSet::v('adm_saas_testconf_ext_url')
			&& $_SESSION['loggedinconf'] == GSet::v('adm_saas_testconf_id')
			&& $confInfo['uid'] != $userinfo['uid']
	){
		header('Location: '.GSet::v('adm_saas_testconf_ext_url'));
		exit();
	}
	header('Location: /newtel');
	exit();
}
function _a_translation(){
	header('Location: '.SITE.'translation/join/');
	exit();
}