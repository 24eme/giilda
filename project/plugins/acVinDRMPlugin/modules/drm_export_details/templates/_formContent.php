<?php use_helper('Float'); ?>

<?php
    echo $form->renderHiddenFields();
    echo $form->renderGlobalErrors();
   $docShow = $detail->hasTypeDoc('export');
?>
<table id="drm_export_details_table" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-5">Pays</th>
            <th class="col-xs-3">Volumes</th>
             <th class="col-xs-2 typedoc_show" <?php echo ($docShow)? '' : 'style="display: none;"' ?> >Type de doc</th>
            <th class="col-xs-1 typedoc_show" <?php echo ($docShow)? '' : 'style="display: none;"' ?> >Numéro&nbsp;de&nbsp;document</th>
            <th class="col-xs-2 text-center typedoc_unshow" <?php echo (!$docShow)? '' : 'style="display: none;"' ?> ><a style="cursor: pointer;" id="type_documents_show"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;type document</a></th>
            <th class="col-xs-1"></th>
        </tr>
    </thead>
    <tbody class="drm_details_tableBody">
    <?php
    foreach ($form as $itemForm){
        if($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode,'docShow' => $docShow));
         } else {
            $itemForm->renderRow();
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td class="col-xs-5" ></td>
            <td class="lead text-right col-xs-3">
                <div class="input-group">
                    <div class="input-group-addon">&Sigma;</div>
                    <input type="text" class="form-control input-float text-right drm_details_volume_total" data-decimal="4" readonly="readonly"  tabindex="-1"  value="<?php echo sprintFloat($detail->get($catKey)->get($key) > 0 ? $detail->get($catKey)->get($key) : "0.00") ?>" />
                    <div class="input-group-addon">hl</div>
                </div>
            </td>
            <td class="col-xs-2 text-center typedoc_unshow"  <?php echo (!$docShow)? '' : 'style="display: none;"' ?>  ></td>
            <td class="col-xs-2 typedoc_show"  <?php echo ($docShow)? '' : 'style="display: none;"' ?>  ></td>
            <td class="col-xs-1 typedoc_show"  <?php echo ($docShow)? '' : 'style="display: none;"' ?>  ></td>
            <td class="text-right col-xs-1"><button type="button" data-container="#drm_export_details_table tbody" data-template="#template_export" class="btn btn-default dynamic-element-add"><span class="glyphicon glyphicon-plus"></span></a></td>
        </tr>
    </tfoot>
</table>

<script>
     $('.drm_details_tableBody').on('keyup','td.volume', $.majSommeLabelBind);

      $("table#drm_export_details_table a#type_documents_show").click(function () {
        $("table#drm_export_details_table").find(".typedoc_show").each(function () {
            $(this).show();
            var content = $('#template_export').html().replace(/style="display: none;"/g,'').replace(/typedoc_unshow\" /g,'typedoc_unshow" style="display: none;"');
        console.log(content);
        $('.modal-body').remove($('script#template_export'))
        $('.modal-body').append('<script id="template_export" class="template_details" type="text/x-jquery-tmpl">'+content+'<//script>');

        });
        $("table#drm_export_details_table").find(".typedoc_unshow").each(function () {
            $(this).hide();
        });

    });

</script>
