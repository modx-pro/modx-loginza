<?php

$Loginza = $modx->getService('loginza','Loginza',$modx->getOption('loginza.core_path',null,$modx->getOption('core_path').'components/loginza/').'model/loginza/',$scriptProperties);
if (!($Loginza instanceof Loginza)) return;


if (isset($rememberme)) {$Loginza->rememberme = $rememberme;}

if (!empty($loginTpl)) {$Loginza->loginTpl = $loginTpl;}
if (!empty($logoutTpl)) {$Loginza->logoutTpl = $logoutTpl;}

if (!empty($saltName)) {$Loginza->saltName = $saltName;}
if (!empty($saltPass)) {$Loginza->saltPass = $saltPass;}

if (!empty($groups)) {$Loginza->groups = $groups;}
if (!empty($loginContext)) {$Loginza->loginContext = $loginContext;}
if (!empty($addContexts)) {$Loginza->addContexts = $addContexts;}

$action = $_REQUEST['action'];

switch ($action) {
	case 'login':
		$Loginza->Login();
		break;

	case 'logout':
		$Loginza->Logout();
		break;

	default:
		echo $Loginza->loadTpl();
}

?>
