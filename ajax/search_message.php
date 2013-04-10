<?php

/**
 * ownCloud - internal_messages
 *
 * @author Jorge Rafael García Ramos
 * @copyright 2012 Jorge Rafael García Ramos <kadukeitor@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

OCP\JSON::checkAppEnabled('internal_messages');
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();

$l = OC_L10N::get('internal_messages');

if ( isset($_POST['pattern']) ) {

	$pattern = OCP\UTIL::sanitizeHTML($_POST['pattern']);
    
    $tmpl = new OCP\Template( 'internal_messages' , 'part.messages' );
    $tmpl->assign( 'messages' , OC_INT_MESSAGES::getMessages( OCP\USER::getUser() , $pattern ) , false );
    $page = $tmpl->fetchPage();

    OCP\JSON::success( array('data' => array( 'page' => $page ) ));

} else {
    OCP\JSON::error(array('data' => array( 'title' => $l->t('Error - Internal Message') , 'message' => $l->t('error when trying search the message.') )));
}
