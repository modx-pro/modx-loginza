<?php
/**
 * Loginza
 *
 * Copyright 2010 by Shaun McCormick <shaun+loginza@modx.com>
 *
 * Loginza is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Loginza is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Loginza; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package loginza
 */
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

return $chunks;
