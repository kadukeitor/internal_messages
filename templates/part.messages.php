<?php

echo "<table>" ;
echo "<tbody>" ;

foreach ($_['messages'] as $message) {

    $show = true ;
    $in   = ( $message['message_owner'] != OCP\USER::getUser() ) ? true : false ; // inbox ??
    
    if ( $in ){
        if ( $message['message_delto'] == 1 ) { $show = false ; }    // inbox & not deleted !!!
    }else{
        if ( $message['message_delowner'] == 1 ) { $show = false ; } // outbox & not deleted !!!   
    }

    if ( $show ) {

        $datetime = OCP\Util::formatDate($message['message_timestamp']);
        $date     = substr($datetime,0, ( strlen($datetime) - 6 ) );
        $time     = substr($datetime,-6);

        if ( ($message['message_read'] == 0) and $in ) { echo "<tr class=\"unread\">" ; } else { echo "<tr>" ; }

        echo "<td><img id=message_photo src=". OC_App::getAppWebPath('user_photo') . '/ajax/showphoto.php?user=' .  $message['message_owner']."></td>" ;

        echo "<td id=msg_content width=100%>";
        echo "<p id=cell_user>".$message['message_owner']." > " . $message['message_to'] . "</p>" ;
        echo "<p message_id=".$message['message_id']." name=message_content>".$message['message_content']."</p>" ;
        echo "</td>";

        echo "<td>" ;
        echo "<p id=cell_date>". $date  ."</p>" ;
        echo "<p id=cell_time>". $time  ."</p>" ;
        echo "</td>" ;

        echo "<td>" ;

        if ($in) { echo "<a href='#' msg_owner=\"".$message['message_owner']."\" class=\"message_action message_reply\" original-title=" . $l->t('Reply'). "><img src=". OC_App::getAppWebPath('internal_messages') ."/img/reply.png></a>" ; }

        echo "<a href='#' msg_id=".$message['message_id']." class=\"message_action message_delete\" original-title=" . $l->t('Delete') . " ><img src=". OC_App::getAppWebPath('internal_messages'). "/img/delete.png></a>" ;
        echo "</td>" ;

        echo "</tr>" ;

    }

}

echo "</table>" ;
echo "</tbody>" ;
