<script id="template_vrac" class="template_details" type="text/x-jquery-tmpl">
    <?php echo include_partial('item', array('form' => $form, 'detail' => $detail,'isTeledeclarationMode' => $isTeledeclarationMode,'docShow' => $detail->getParent()->hasTypeDoc())); ?>
</script>