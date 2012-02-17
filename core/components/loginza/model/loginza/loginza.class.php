<?php

class Loginza {
	var $rememberme = true;

	var $loginTpl = 'tpl.Loginza.login';
	var $logoutTpl = 'tpl.Loginza.logout';

	var $saltName = '';
	var $saltPass = '';

	var $groups = '';
	var $loginContext = '';
	var $addContexts = '';
	var $updateProfile = 1;

    function __construct(modX &$modx) {
        $this->modx =& $modx;
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
		$username = md5($arr['identity'].$this->saltName);
		$password = md5($arr['identity'].$this->saltPass);;

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

		// Если юзер заходит первый раз - создаем ему учетную запись
		if (!$this->modx->getObject('modUser', array('username' => $username))) {
			$user = $this->modx->newObject('modUser', array('username' => $username, 'password' => $password));

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
		$user = $this->modx->getObject('modUser', array('username' => $username));

		// Обновляем профиль юзера, усли указано его обновлять, или он только что создан.
		if ($this->updateProfile || $newuser) {
			$profile = $user->getOne('Profile');

			$profile->set('fullname', $fullname);
			$profile->set('email', $email);
			$profile->set('dob', $dob);
			$profile->set('gender', $gender);
			$profile->set('website', $provider);
			$profile->set('comment', $identity);
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

	function loadTpl($arr = array()) {
		if ($this->modx->user->isAuthenticated()) {
			$arr = $this->modx->user->getOne('Profile')->toArray();

			return $this->modx->getChunk($this->logoutTpl, $arr);
		}
		else {
			return $this->modx->getChunk($this->loginTpl);
		}
	}

	function Refresh() {
		header('Location: '.$this->modx->makeUrl($this->modx->resource->id, '', '', 'full'));
	}

}

?>
