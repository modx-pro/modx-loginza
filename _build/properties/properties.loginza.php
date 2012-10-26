<?php
/**
 * Properties for the Loginza snippet.
 *
 * @package loginza
 * @subpackage build
 */
$properties = array();

$properties[0] = array(
	array(
		'name' => 'rememberme',
		'value' => true,
		'type' => 'combo-boolean',
		'desc' => 'If true, user will receive long session',
	),
	array(
		'name' => 'loginTpl',
		'value' => 'tpl.Loginza.login',
		'type' => 'textfield',
		'desc' => 'Chunk for anonymous user',
	),
	array(
		'name' => 'logoutTpl',
		'value' => 'tpl.Loginza.logout',
		'type' => 'textfield',
		'desc' => 'Chunk for authenticated user',
	),
	array(
		'name' => 'profileTpl',
		'value' => 'tpl.Loginza.profile',
		'type' => 'textfield',
		'desc' => 'Chunk for display and edit user profile',
	),
	array(
		'name' => 'saltName',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'Salt for generated user key',
	),
	array(
		'name' => 'saltPass',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'Salt for generated user password',
	),
	array(
		'name' => 'groups',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'Comma separated list of existing user groups for joining by user at the first login',
	),
	array(
		'name' => 'loginContext',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'Main context for authentication',
	),
	array(
		'name' => 'addContexts',
		'value' => '',
		'type' => 'textfield',
		'desc' => 'Comma separated list of additional contexts for authentication',
	),
	array(
		'name' => 'updateProfile',
		'value' => true,
		'type' => 'combo-boolean',
		'desc' => 'If true, user profile will be updated by data from remote service on every login',
	),
	array(
		'name' => 'profileFields',
		'value' => 'username,email,fullname,phone,mobilephone,dob,gender,address,country,city,state,zip,fax,photo,comment,website',
		'type' => 'textfield',
		'desc' => 'Comma separated list of allowed user fields for update',
	),
	array(
		'name' => 'action',
		'value' => 'loadTpl',
		'type' => 'list',
		'desc' => 'Action of the snippet',
		'options' => array(
			array('text' => 'login','value' => 'login'),
			array('text' => 'logout','value' => 'logout'),
			array('text' => 'getProfile','value' => 'getProfile'),
			array('text' => 'updateProfile','value' => 'updateProfile'),
			array('text' => 'loadTpl','value' => 'loadTpl'),
		),
	),
	array(
		'name' => 'requiredFields',
		'value' => 'username,email,fullname',
		'type' => 'textfield',
		'desc' => 'Comma separated list of required user fields when update',
	),
	array(
		'name' => 'loginResourceId',
		'value' => 0,
		'type' => 'numberfield',
		'desc' => 'Resource id to redirect to on successful login. 0 will redirect to self.',
	),
	array(
		'name' => 'logoutResourceId',
		'value' => 0,
		'type' => 'numberfield',
		'desc' => 'Resource id to redirect to on successful logout. 0 will redirect to self.',
	),
);

return $properties;
?>