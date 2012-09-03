<?php
require MODX_CORE_PATH.'model/modx/processors/security/user/update.class.php';

class LoginzaUpdateProcessor extends modUserUpdateProcessor {
	public $permission = '';
    public $languageTopics = array('user','default');
	
	public function initialize() {
		$this->setProperty('id', $this->modx->user->id);

		return parent::initialize();
	}
	
	public function beforeSet() {
        $fields = $this->getProperty('requiredFields', '');
        
        if (!empty($fields) && is_array($fields)) {
            foreach ($fields as $field) {
                $tmp = trim($this->getProperty($field,null));
                if ($field == 'email') {
                    if (!filter_var($tmp, FILTER_VALIDATE_EMAIL)) {
                    	$this->addFieldError('email', $this->modx->lexicon('user_err_not_specified_email'));
                    }
                }
                else if (empty($tmp)) {
                    $this->addFieldError($field, $this->modx->lexicon('field_required'));
                }
            }
        }

		if ($this->hasErrors) {
			return false;
		}
		else {
			return parent::beforeSet();
		}
	}

}

return 'LoginzaUpdateProcessor';