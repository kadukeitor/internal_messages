<?php

class OC_INT_MESSAGES_Hooks
{

    public static function post_shared( $parameters )
    {

        $l = OC_L10N::get('internal_messages');

        $msgto   = array( array( $parameters['shareWith']  ) ) ;

        $type    = $parameters['itemType'] == 'file' ? $l->t('the file ') : $l->t('the folder ') ;

        $path    = \OC_FileCache::getPath( $parameters['itemSource'] ,OCP\USER::getUser()  ) ;

        $msgcontent  = $l->t('I shared with you ') . $type . basename($path) ;

        OC_INT_MESSAGES::sendMessage( OCP\USER::getUser() , $msgto , $msgcontent , 'ns' , 1 ) ;

        return true ;

    }

}
