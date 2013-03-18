<?php

OC::$CLASSPATH['OC_INT_MESSAGES']       = 'apps/internal_messages/lib/internalmessages.php';
OC::$CLASSPATH['OC_INT_MESSAGES_Hooks'] = 'apps/internal_messages/lib/hooks.php';

$l = OC_L10N::get('internal_messages');

OCP\Util::connectHook('OCP\Share', 'post_shared', 'OC_INT_MESSAGES_Hooks', 'post_shared');

OCP\Util::addscript('internal_messages','messages');
OCP\Util::addStyle ('internal_messages','style');

$unread = OC_INT_MESSAGES::unreadMessages( OCP\USER::getUser() );
if ($unread) { 
	$name = $l->t('Messages') . "(".$unread.")" ; 
	$icon = 'message_red.png' ;
} else { 
	$name = $l->t('Messages') ; 
	$icon = 'message.png' ;
}

OCP\App::addNavigationEntry(
    array( 'id' => 'internal_messages_index',
           'order' => 74,
           'href' => OCP\Util::linkTo( 'internal_messages' , 'index.php' ),
           'icon' => OCP\Util::imagePath( 'internal_messages', $icon ),
           'name' => $name  )
   );
