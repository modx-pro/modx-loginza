<?php
/**
 * Add chunks to build
 * 
 * @package loginza
 * @subpackage build
 */
$snippets = array();

$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.Loginza.login',
    'description' => 'Loginza login.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/login.tpl'),
),'',true,true);

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.Loginza.logout',
    'description' => 'Loginza logout.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/logout.tpl'),
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.Loginza.profile',
    'description' => 'User profile.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/profile.tpl'),
),'',true,true);

return $chunks;
