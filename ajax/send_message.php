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

$msgto       = isset($_POST['msgto']) ? $_POST['msgto'] : false;
$msgcontent  = isset($_POST['msgcontent']) ? OCP\UTIL::sanitizeHTML($_POST['msgcontent']) : false;

if ($msgto && $msgcontent ) {
    
    if ( OC_INT_MESSAGES::sendMessage( OCP\USER::getUser() , $msgto , $msgcontent ) ) {

        $tmpl = new OCP\Template( 'internal_messages' , 'part.messages' );
        $tmpl->assign( 'messages' , OC_INT_MESSAGES::getMessages( OCP\USER::getUser() ) , false );
        $page = $tmpl->fetchPage();

        OCP\JSON::success(array('data' => array( 'title' => $l->t('Success - Internal Message') , 'message' => $l->t('The message has been sent.') , 'page' => $page  )));

    } else {
        OCP\JSON::error(array('data' => array( 'title' => $l->t('Error - Internal Message') , 'message' => $l->t("error when trying to sent the message.\ncheck the sender and the content.") )));
    }
} else {
    OCP\JSON::error(array('data' => array( 'title' => $l->t('Error - Internal Message') , 'message' => $l->t("error when trying to sent the message.\nall fields must be filled.") )));
}
