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

class OC_INT_MESSAGES
{
    const flag_group_part = 'gp';
    const flag_group_mesg = 'gm';

    public static function delMessage ( $id )
    {

        $query   = OCP\DB::prepare('SELECT * FROM *PREFIX*internal_messages WHERE message_id = ?');
        $result  = $query->execute(array( $id ));
        $message = $result->fetchAll();

        if ( $message[0]['message_owner'] == OCP\USER::getUser() ) { //
            if ( $message[0]['message_delto']  == 1){
                $query = OCP\DB::prepare( "DELETE FROM `*PREFIX*internal_messages` WHERE message_id = ?" );
                $query->execute(array( $id ));             
            } else {
                $query = OCP\DB::prepare('UPDATE *PREFIX*internal_messages SET message_delowner=? WHERE message_id=?');
                $query->execute(array(1,$id));
            }
        } else {
            if ( $message[0]['message_delowner'] == 1 ){
                $query = OCP\DB::prepare( "DELETE FROM `*PREFIX*internal_messages` WHERE message_id = ?" );
                $query->execute(array( $id ));  
            }else{            
                $query = OCP\DB::prepare('UPDATE *PREFIX*internal_messages SET message_delto=? WHERE message_id=?');
                $query->execute(array(1,$id));
            }
        }

        return true ;        

    }

    public static function sendMessage ( $msgfrom , $msgto ,  $msgcontent , $msgflag='' , $msgdelowner = 0 )
    {
         
         if ( is_array($msgto[0]) ) {
             foreach ($msgto[0] as $user) {
                
                $query = OCP\DB::prepare('INSERT INTO *PREFIX*internal_messages (message_owner,message_to,message_timestamp,message_content,message_delowner,message_flag) VALUES (?,?,?,?,?,?)');
                $query->execute( Array( $msgfrom,$user,time(), $msgcontent, $msgdelowner , $msgflag ));
                
            }
         }

         if ( is_array($msgto[1]) ) {
             foreach ($msgto[1] as $group) {
                $groupUsers = OC_Group::usersInGroup( $group );
                foreach ($groupUsers as $user) {
                    if ($user != $msgfrom) {
                        self::sendMessage( $msgfrom , array( array( $user ) ) , $msgcontent , self::flag_group_part , 1 ) ;
                    }
                }

                $query = OCP\DB::prepare('INSERT INTO *PREFIX*internal_messages (message_owner,message_to,message_timestamp,message_content,message_delto,message_flag) VALUES (?,?,?,?,?,?)');
                $query->execute(Array($msgfrom,$group.'(group)',time(),$msgcontent, 1 , self::flag_group_mesg ));
            }
         }

         return true;

    }

    public static function unreadMessages($user)
    {
        $query  = OCP\DB::prepare('SELECT * FROM *PREFIX*internal_messages WHERE message_to = ? AND message_delto = 0 AND message_read = 0');
        $result = $query->execute(Array( $user ));
        $msgs   = $result->fetchAll();

        return count($msgs);

    }

    public static function readMessages( $user )
    {
        $query  = OCP\DB::prepare('UPDATE *PREFIX*internal_messages SET message_read = 1 WHERE message_read= 0 AND message_to = ?');
        $result = $query->execute(Array( $user ));

        return 1;

    }

    public static function getMessages ( $user , $pattern = '' )
    {
        $pattern = '%' . $pattern . '%' ;

        $query  = OCP\DB::prepare('SELECT * FROM *PREFIX*internal_messages
                                    WHERE
                                         ( message_to = ? OR message_owner = ? )
                                         AND ( message_delto = 0 OR message_delowner = 0 )
                                         AND message_content LIKE ?
                                    ORDER by message_timestamp DESC');
        $result = $query->execute( array( $user , $user , $pattern ));

        return $result->fetchAll() ;

    }

}
