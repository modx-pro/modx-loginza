<?php
require MODX_CORE_PATH.'model/modx/processors/security/user/update.class.php';

class LoginzaUpdateProcessor extends modUserUpdateProcessor {
	var $permission = '';

	public function initialize() {
		$this->setProperty('id', $this->modx->user->id);

		return parent::initialize();
	}

}

return 'LoginzaUpdateProcessor';