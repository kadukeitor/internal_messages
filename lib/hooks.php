<?php

/**
 * ownCloud - internal_messages
 *
 * @author Jorge Rafael Garc�a Ramos
 * @copyright 2012 Jorge Rafael Garc�a Ramos <kadukeitor@gmail.com>
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

class OC_INT_MESSAGES_Hooks
{

    public static function post_shared( $parameters )
    {

        $l = OC_L10N::get('internal_messages');

        $type = $parameters['shareType'] ;
        
        if ( $type == OCP\Share::SHARE_TYPE_USER or $type == OCP\Share::SHARE_TYPE_GROUP )  {

            $msgto   = array( array( $parameters['shareWith']  ) ) ;
            $type    = $parameters['itemType'] == 'file' ? $l->t('the file ') : $l->t('the folder ') ;
            $item    = substr($parameters['fileTarget'],1);

            $msgcontent  = $l->t('I shared with you ') . $item ;
            OC_INT_MESSAGES::sendMessage( OCP\USER::getUser() , $msgto , $msgcontent , 'ns' , 1 ) ;

            return true ;

        } else {

            return false ;

        }

    }

}
