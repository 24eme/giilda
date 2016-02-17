<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
$docShow = true;
?>
<table id="drm_vrac_details_table" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-5">Numéro contrat</th>
            <th class="col-xs-3">Volumes</th>

            <th class="col-xs-2 typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >Type de doc</th>
            <th class="col-xs-2 typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >Numéro&nbsp;de&nbsp;document</th> 


            <th class="col-xs-3 text-center typedoc_unshow" <?php echo (!$docShow) ? '' : 'style="display: none;"' ?> ><a style="cursor: pointer;" id="type_documents_show"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;type document</a></th>            


            <th class="col-xs-1"></th>
        </tr>
    </thead>
    <tbody class="drm_details_tableBody">
        <?php
        foreach ($form as $itemForm) {
            if ($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode, 'docShow' => true));
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
                    <input type="text" class="form-control text-right drm_details_volume_total" readonly="readonly" value="<?php echo $detail->sorties->vrac > 0 ? $detail->sorties->vrac : "0.00" ?>" />
                    <div class="input-group-addon">hl</div>
                </div>
            </td>           
            <td class="col-xs-3 text-center typedoc_unshow"  <?php echo (!$docShow) ? '' : 'style="display: none;"' ?>  ></td>
            <td class="col-xs-2 typedoc_show"  <?php echo ($docShow) ? '' : 'style="display: none;"' ?>  ></td>
            <td class="col-xs-2 typedoc_show"  <?php echo ($docShow) ? '' : 'style="display: none;"' ?>  ></td>
            <td class="text-right col-xs-1"><button type="button" data-container="#drm_vrac_details_table tbody" data-template="#template_vrac" class="btn btn-default dynamic-element-add"><span class="glyphicon glyphicon-plus"></span></a></td>
        </tr>
    </tfoot>
</table>

<script>
     $('.drm_details_tableBody').on('keyup','td.volume', $.majSommeLabelBind);
    
//    $("table#drm_vrac_details_table a#type_documents_show").click(function () {
//        $("table#drm_vrac_details_table").find(".typedoc_show").each(function () {
//            $(this).show();
//        });
//        $("table#drm_vrac_details_table").find(".typedoc_unshow").each(function () {
//            $(this).hide();
//        });
//        $(this).attr('id', 'type_documents_unshow');
//
//    });



</script>
