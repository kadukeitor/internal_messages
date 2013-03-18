
<div id="controls">
    <form id="view" href="javascript:void('')">
        <input type="button" value="<?php echo " ".$l->t('Write Message')." ";?>" id="create_message"/>
        <div class="separator"></div>
        <label for="search_messages"><?php echo $l->t('Search').":";?></label><input type="text" id="search_messages">
        <img id="loading" src="<?php echo OCP\Util::imagePath('core', 'loading.gif'); ?>" />
    </form>
</div>

<div id="messages_wall">

    <?php
        echo $this->inc('part.messages');
    ?>

</div>

<div id="dialog_holder"></div>
