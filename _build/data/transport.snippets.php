<?php
/**
 * Add snippets to build
 * 
 * @package loginza
 * @subpackage build
 */
$snippets = array();
$properties = include $sources['build'].'properties/properties.loginza.php';

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Loginza',
    'description' => 'Loginza is an identification system that provides a single method of logging into popular web-services.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/loginza.php'),
),'',true,true);
$snippets[0]->setProperties($properties[0]);

return $snippets;
