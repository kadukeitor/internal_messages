<script type="text/javascript">
    OC.InternalMessages.initDropDown();

</script>
<div id="writemessage_dialog" title="<?php echo $l -> t("Write Message");?>">

    <table width="100%" style="border: 0; margin-top: 1em">
        <tr>
            <td>
            <input type="text" id="to_message" placeholder="<?php echo $l -> t("Message to ...");?>" />
            </td>
        </tr>
        <tr>
            <td>
            <ul class="sendto msglist">
            </ul>
            </td>
        </tr>
        <tr>
            <td>
                <textarea id="content_message" placeholder="<?php echo $l -> t("content ...");?>" cols=50 rows=5 style="width: 95%;"></textarea>
            </td>
        </tr>
        <tr>
            <td style="padding: 0.5em; text-align: right;"><a id="send_message" class="button" href="#"><?php echo $l->t('Send Message')
            ?></a></td>
        </tr>
    </table>
