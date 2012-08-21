<?php
//ini_set('display_errors', 1);
//ini_set('error_reporting', -1);

$Loginza = $modx->getService('loginza','Loginza', $modx->getOption('core_path').'components/loginza/model/loginza/',$scriptProperties);
if (!($Loginza instanceof Loginza)) return;

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