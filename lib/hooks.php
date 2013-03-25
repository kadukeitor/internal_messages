<?php

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
