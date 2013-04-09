OC.InternalMessages = {

    search : ['p'],

    mesgto : [],

    SearchMessage : function(event) {

        var pattern = $('#search_messages').val();
        $.post(OC.filePath('internal_messages', 'ajax', 'search_message.php'), {
            pattern : pattern,
        }, function(jsondata) {
            if(jsondata.status == 'success') {
                document.getElementById('messages_wall').innerHTML = jsondata.data.page;
                var search = document.getElementsByName("message_content");
                $.each(search, function(i) {
                    var str = search[i];
                    var orgText = $(str).text();
                    orgText = orgText.replace(pattern, function($1) {
                        return "<span style='background-color: yellow;'>" + $1 + "</span>"
                    });
                    $(str).html(orgText);
                });
            } else {
                OC.dialogs.alert(jsondata.data.message, jsondata.data.title  );
            }
        }, 'json');
    },
    
    SendMessage : function() {

        var msgcontent = $("#content_message").val().trim();
        
        $.post(OC.filePath('internal_messages', 'ajax', 'send_message.php'), {
            msgto      : OC.InternalMessages.mesgto ,
            msgcontent : msgcontent ,
        }, function(jsondata) {
            if(jsondata.status == 'success') {
                $('#writemessage_dialog').dialog('destroy').remove();
                document.getElementById('messages_wall').innerHTML = jsondata.data.page;
                OC.dialogs.alert(jsondata.data.message, jsondata.data.title );
            } else {
                OC.dialogs.alert(jsondata.data.message, jsondata.data.title );
            }
        }, 'json');

    },
    
    DelMessage : function(id) {

        $('.tipsy').remove();

        $.post(OC.filePath('internal_messages', 'ajax', 'del_message.php'), {
            id : id,
        }, function(jsondata) {
        }, 'json');

    },
    
    ReplyMessage : function(owner) {

        $('.tipsy').remove();
        
        $('#dialog_holder').load(OC.filePath('internal_messages', 'ajax', 'write_message.php'), function(response) {
            if(response.status != 'error') {
                
                $('#writemessage_dialog').dialog({
                    minWidth : 500,
                    modal : true,
                    close : function(event, ui) {
                        $(this).dialog('destroy').remove();
                    }
                }).css('overflow', 'visible');

                var msgType = OC.Share.SHARE_TYPE_USER;
                var msgTo = owner;
                var newitem = '<li ' + 'data-message-to="' + msgTo 
                            + '" ' + 'data-message-type="' + msgType + '">' + msgTo 
                            + ' (' + (msgType == OC.Share.SHARE_TYPE_USER ? t('core', 'user') : t('core', 'group')) + ')' 
                            +'<span class="msgactions">'+ '<img class="svg action delete" title="' + t('internal_messages', 'Quit') + '" src="' 
                            + OC.imagePath('core', 'actions/delete.svg') + '"></span></li>';
                $('.sendto.msglist').append(newitem);
                OC.InternalMessages.mesgto[msgType].push(msgTo);
                $('#content_message').focus();

            }
        });

    },
    
    initDropDown : function() {

        OC.InternalMessages.mesgto[OC.Share.SHARE_TYPE_USER] = [];
        OC.InternalMessages.mesgto[OC.Share.SHARE_TYPE_GROUP] = [];

        $('#to_message').autocomplete({
            minLength : 2,
            source : function(search, response) {
                $.get(OC.filePath('core', 'ajax', 'share.php'), {
                    fetch : 'getShareWith',
                    search : search.term,
                    itemShares : [OC.InternalMessages.mesgto[OC.Share.SHARE_TYPE_USER], OC.InternalMessages.mesgto[OC.Share.SHARE_TYPE_GROUP]]
                }, function(result) {
                    if(result.status == 'success' && result.data.length > 0) {
                        response(result.data);
                    }
                });
            },
            focus : function(event, focused) {
                event.preventDefault();
            },
            select : function(event, selected) {
                var msgType = selected.item.value.shareType;
                var msgTo = selected.item.value.shareWith;
                var newitem = '<li ' + 'data-message-to="' + msgTo 
                            + '" ' + 'data-message-type="' + msgType + '">' + msgTo 
                            + ' (' + (msgType == OC.Share.SHARE_TYPE_USER ? t('core', 'user') : t('core', 'group')) + ')' 
                            +'<span class="msgactions">'+ '<img class="svg action delete" title="' + t('internal_messages', 'Quit') + '" src="' 
                            + OC.imagePath('core', 'actions/delete.svg') + '"></span></li>';
                $('.sendto.msglist').append(newitem);
                $('#sharewith').val('');
                OC.InternalMessages.mesgto[msgType].push(msgTo);
                return false;
            },
        });
    }
}

$(document).ready(function() {

    $('.msgactions > .delete').live('click', function() {   
        var container = $(this).parents('li').first();
        var msgType = container.data('message-type');
        var msgTo = container.data('message-to');
        container.remove();
        var index = OC.InternalMessages.mesgto[msgType].indexOf(msgTo);
        OC.InternalMessages.mesgto[msgType].splice(index, 1);
    });

    $(window).resize(function() {
        fillWindow($('#messages_wall'));
    });
    $(window).trigger('resize');

    $('#send_message').live('click', OC.InternalMessages.SendMessage);

    $('#search_messages').live('keyup', OC.InternalMessages.SearchMessage);
    
    $('#search_messages').live('keydown', function(event) {
        if(event.keyCode == 13 || event.keyCode == 27) {
            return false;
        }
    });

    $('#create_message').click(function() {

        $('#dialog_holder').load(OC.filePath('internal_messages', 'ajax', 'write_message.php'), function(response) {
            if(response.status != 'error') {
                $('#writemessage_dialog').dialog({
                    minWidth : 500,
                    modal : true,
                    close : function(event, ui) {
                        $(this).dialog('destroy').remove();
                    }
                }).css('overflow', 'visible');
            }
        });

    });

    $('.message_delete').live('click', function() {
        OC.InternalMessages.DelMessage($(this).attr('msg_id'));
        $(this).parent().parent().remove();
    });

    $('.message_reply').live('click', function() {
        OC.InternalMessages.ReplyMessage($(this).attr('msg_owner'));
    });

    $('#bbcode_help').live('click', function() {
        window.open(OC.filePath('internal_messages', 'templates', 'bbcode.php'), 'help', 'width=300,height=400,left=100,top=200');
    });   

    $('a.message_action').tipsy({
        gravity : 's',
        fade : true,
        live : true
    });

})
