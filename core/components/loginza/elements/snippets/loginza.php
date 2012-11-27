<?php
$Loginza = $modx->getService('loginza','Loginza', $modx->getOption('core_path').'components/loginza/model/loginza/',$scriptProperties);
if (!($Loginza instanceof Loginza)) return;

// If user sends action
if (!empty($_REQUEST['action'])) {
	// And it is login or logout - it will override any action
	if (in_array($_REQUEST['action'], array('login','logout'))) {$action = $_REQUEST['action'];}
	// And he wants to update his profile - it will be handled only by snippet that called with action getProfile
	else if ($_REQUEST['action'] == 'updateProfile' && $modx->getOption('action', $scriptProperties) == 'getProfile') {$action = 'updateProfile';}
}

if (empty($action)) {$action = $modx->getOption('action', $scriptProperties, 'loadTpl');}

switch ($action) {
	case 'login': $Loginza->Login(); break;
	case 'logout': $Loginza->Logout(); break;
	case 'getProfile': return $Loginza->getProfile(); break;
	case 'updateProfile': return $Loginza->updateProfile(); break;
	case 'loadTpl': 
	default: return $Loginza->loadTpl(); break;
}
