<?php
/**
 * Loginza
 *
 * Copyright 2010 by Shaun McCormick <shaun+eventscalendar2@modx.com>
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
 * @package Loginza
 */
/**
 * Add snippets to build
 * 
 * @package loginza
 * @subpackage build
 */
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Loginza',
    'description' => 'Loginza is an identification system that provides a single method of logging into popular web-services.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/loginza.php'),
),'',true,true);

return $snippets;
