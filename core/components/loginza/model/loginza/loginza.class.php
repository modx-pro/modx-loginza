<?php

class Loginza {
	var $rememberme = true;

	var $loginTpl = 'tpl.Loginza.login';
	var $logoutTpl = 'tpl.Loginza.logout';
	var $profileTpl = 'tpl.Loginza.profile';

	var $saltName = '';
	var $saltPass = '';

	var $groups = '';
	var $loginContext = '';
	var $addContexts = '';
	var $updateProfile = 1;
	var $profileFields = 'username,email,fullname,phone,mobilephone,dob,gender,address,country,city,state,zip,fax,photo,comment,website';

	function __construct(modX &$modx,array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('loginza.core_path',$config,$this->modx->getOption('core_path').'components/loginza/');
		
		$this->config = array_merge(array(
			'corePath' => $corePath,
			'modelPath' => $corePath.'model/',
			'chunksPath' => $corePath.'elements/chunks/',
			'snippetsPath' => $corePath.'elements/snippets/',
			'processorsPath' => $corePath.'processors/',
		),$config);
	}

	function Login() {
		if (strpos($_SERVER['HTTP_REFERER'], 'loginza') == false) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Loginza: invalid http referer');
			return $this->Refresh();
		}
		if (empty($_POST['token'])) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Loginza: invalid token');
			return $this->Refresh();
		}

		$opt = file_get_contents('http://loginza.ru/api/authinfo?token='.$_POST['token'], r);
		$arr = json_decode($opt, true);

		if (empty($arr['identity'])) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Loginza: received broken user array');
			return $this->Refresh();
		}

		$identity = $arr['identity'];
		$userkey = md5($arr['identity'].$this->saltName);
		$password = md5($arr['identity'].$this->saltPass);;
		$username = $this->modx->sanitizeString($arr['nickname']);
			if (empty($username)) {
				$username = $userkey;
			}
		$fullname = $arr['name']['full_name'];
			if (empty($fullname)) {
				$fullname = $arr['name']['first_name'].' '.$arr['name']['last_name'];
			}
		$provider = $arr['provider'];
		$email = $arr['email'];
		$g = $arr['gender'];
			if ($g == 'M') {$gender = 1;}
			else if ($g == 'F') {$gender = 2;}
			else {$gender = 0;}
		if (!empty($arr['dob'])) {
			list($y,$m,$d) = explode('-',$arr['dob']);
			$dob = @mktime(0,0,0, $m,$d,$y);
		}
		else {$dob = 0;}

		// Меняем расположение ключа для юзеров версии 1.1.*
		if ($user = $this->modx->getObject('modUser', array('username' => $userkey, 'remote_key' => null))) {
			$user->set('remote_key', $userkey);
			$user->set('username', $username);
			$user->save();
		}
		
		// Если юзер заходит первый раз - создаем ему учетную запись
		if (!$this->modx->getObject('modUser', array('remote_key' => $userkey))) {
			$user = $this->modx->newObject('modUser', array('remote_key' => $userkey, 'password' => $password));
			
			// Проверяем занятость имени юзера
			if ($exists = $this->modx->getCount('modUser', array('username' => $username))) {
				$user->set('username', $username.($exists +1));
			}
			else {
				$user->set('username', $username);
			}
			
			// Профиль юзера, мы его обновим чуть позже
			$userProfile = $this->modx->newObject('modUserProfile');
			$user->addOne($userProfile);

			// Если указано - заносим в группы
			if (!empty($this->groups)) {
				$groups = explode(',', $this->groups);

				$userGroups = array();
				foreach ($groups as $group) {
					$group = trim($group);

					if ($tmp = $this->modx->getObject('modUserGroup', array('name' => $group))) {
						$gid = $tmp->get('id');
						$userGroup = $this->modx->newObject('modUserGroupMember');
						$userGroup->set('user_group', $gid);
						$userGroup->set('role', 1);

						$userGroups[] = $userGroup;
					}
				}
				$user->addMany($userGroups);
			}

			$user->save();
			$newuser = 1;
		}

		// Получаем юзера
		$user = $this->modx->getObject('modUser', array('remote_key' => $userkey));
		$username = $user->get('username');

		// Обновляем профиль юзера, усли указано его обновлять, или он только что создан.
		if ($this->updateProfile || $newuser) {
			$profile = $user->getOne('Profile');

			$profile->set('fullname', $this->modx->sanitizeString($fullname));
			$profile->set('email', strip_tags($email));
			$profile->set('dob', strip_tags($dob));
			$profile->set('gender', strip_tags($gender));
			$profile->set('website', strip_tags($provider));
			$profile->set('comment', strip_tags($identity));
			$profile->save();
		}

		$data = array(
			'username' => $username,
			'password' => $password,
			'rememberme' => $this->rememberme
		);
		if (!empty($this->loginContext)) {$data['login_context'] = $this->loginContext;}
		if (!empty($this->addContexts)) {$data['add_contexts'] = $this->addContexts;}

		// Логиним юзера
		$response = $this->modx->runProcessor('/security/login', $data);
		if ($response->isError()) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Loginza: login error. Username: '.$username.', uid: '.$user->get('id').'. Message: '.$response->getMessage());
		}

		return $this->Refresh();
	}


	function Logout() {
		$response = $this->modx->runProcessor('/security/logout');
		if ($response->isError()) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Loginza: logout error. Username: '.$this->modx->user->get('username').', uid: '.$this->modx->user->get('id').'. Message: '.$response->getMessage());
		}
		return $this->Refresh();
	}


	function getProfile($data = array()) {
		if (!$this->modx->user->isAuthenticated()) {
			return $this->modx->sendForward($this->modx->getOption('unauthorized_page'));
		}

		$user = $this->modx->user;
		$profile = $this->modx->user->Profile;
		$arr = array_merge($user->toArray(), $profile->toArray(), $data);

		return $this->modx->getChunk($this->profileTpl, $arr);
	}


	function updateProfile() {
		if (!$this->modx->user->isAuthenticated()) {
			return $this->Refresh();
		}

		$data = $errors = array();
		$fields = explode(',', $this->profileFields);
		foreach ($fields as $field) {
			if (!empty($_POST[$field])) {$data[$field] = $_POST[$field];}
		}
		
		$response = $this->modx->runProcessor('web/user/update', $data, array(
				'processors_path' => $this->config['processorsPath']
			)
		);
		if ($response->isError()) {
			foreach ($response->errors as $error) {
				$errors['error.'.$error->field] = $error->message;
			}
			$errors['success'] = 0;
		}
		else {$errors['success'] = 1;}
		
		return $this->getProfile($errors);
	}


	function loadTpl($arr = array()) {
		$url = $this->modx->makeUrl($this->modx->resource->id, '', '', 'full');
		if ($this->modx->getOption('friendly_urls')) {$url .= '?action=';}
		else {$url .= '&action=';}
		
		if ($this->modx->user->isAuthenticated()) {
			$user = $this->modx->user->toArray();
			$profile = $this->modx->user->getOne('Profile')->toArray();
			$arr = array_merge($user,$profile);
			$arr['logout_url'] = $url.'logout';

			return $this->modx->getChunk($this->logoutTpl, $arr);
		}
		else {
			$arr = array('login_url' => urlencode($url.'login'));

			return $this->modx->getChunk($this->loginTpl, $arr);
		}
	}

	function Refresh() {
		$this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id, '', '', 'full'));
	}

}

?>
