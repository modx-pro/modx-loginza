<?php
ini_set('display_errors', 1);
ini_set('error_reporting', -1);

$Loginza = $modx->getService('loginza','Loginza',$modx->getOption('loginza.core_path',null,$modx->getOption('core_path').'components/loginza/').'model/loginza/',$scriptProperties);
if (!($Loginza instanceof Loginza)) return;

if (isset($rememberme)) {$Loginza->rememberme = $rememberme;}

if (!empty($loginTpl)) {$Loginza->loginTpl = $loginTpl;}
if (!empty($logoutTpl)) {$Loginza->logoutTpl = $logoutTpl;}
if (!empty($profileTpl)) {$Loginza->profileTpl = $profileTpl;}

if (!empty($saltName)) {$Loginza->saltName = $saltName;}
if (!empty($saltPass)) {$Loginza->saltPass = $saltPass;}

if (!empty($groups)) {$Loginza->groups = $groups;}
if (!empty($loginContext)) {$Loginza->loginContext = $loginContext;}
if (!empty($addContexts)) {$Loginza->addContexts = $addContexts;}

if (isset($updateProfile)) {$Loginza->updateProfile = $updateProfile;}
if (!empty($profileFields)) {$Loginza->profileFields = $profileFields;}

if (empty($_REQUEST['action'])) {$action = $modx->getOption('action', $scriptProperties, 'loadTpl');}
else {$action = $_REQUEST['action'];}

switch ($action) {
	case 'login': $Loginza->Login(); break;
	case 'logout': $Loginza->Logout(); break;
	case 'getProfile': return $Loginza->getProfile(); break;
	case 'updateProfile': return $Loginza->updateProfile(); break;
	case 'loadTpl': 
	default: return $Loginza->loadTpl(); break;
}