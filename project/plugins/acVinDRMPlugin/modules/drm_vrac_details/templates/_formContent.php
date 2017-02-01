<?php use_helper('PointsAides'); ?>
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
$docShow = $detail->hasTypeDoc('vrac');
?>

<table id="drm_vrac_details_table" class="table table-striped <?php echo ($docShow) ? 'typedoc_show' : 'typedoc_unshow'; ?>">
    <thead>
        <tr>
            <th class="col-xs-4">Numéro contrat<?php echo getPointAideHtml('drm','mouvements_contrats_numero') ?></th>
            <th class="col-xs-3">Volumes</th>

            <th class="col-xs-2 typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >Type de doc.<?php echo getPointAideHtml('drm','mouvements_contrats_type_doc') ?></th>
            <th class="col-xs-2 typedoc_show" <?php echo ($docShow) ? '' : 'style="display: none;"' ?> >Num.&nbsp;du&nbsp;doc.<?php echo getPointAideHtml('drm','mouvements_contrats_doc_accompagnement_num') ?></th>


            <th class="col-xs-2 text-center typedoc_unshow" <?php echo (!$docShow) ? '' : 'style="display: none;"' ?> ><a style="cursor: pointer;" id="type_documents_show"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Documents</a><?php echo getPointAideHtml('drm','mouvements_contrats_doc_accompagnement') ?></th>


            <th class="col-xs-2"></th>
        </tr>
    </thead>
    <tbody class="drm_details_tableBody">
        <?php
        foreach ($form as $itemForm) {
            if ($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode, 'docShow' => $docShow));
            } else {
                $itemForm->renderRow();
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td class="col-xs-4" ></td>
            <td class="lead text-right col-xs-3">
                <div class="input-group">
                    <div class="input-group-addon">&Sigma;</div>
                    <input type="text" class="form-control text-right drm_details_volume_total" tabindex="-1" readonly="readonly" value="<?php echo $detail->get($catKey)->get($key) > 0 ? $detail->get($catKey)->get($key) : "0.00" ?>" />
                    <div class="input-group-addon">hl</div>
                </div>
            </td>
            <td class="col-xs-2 typedoc_show"  <?php echo ($docShow) ? '' : 'style="display: none;"' ?>  ></td>
            <td class="col-xs-1 typedoc_show"  <?php echo ($docShow) ? '' : 'style="display: none;"' ?>  ></td>

            <td class="col-xs-2 text-center typedoc_unshow"  <?php echo (!$docShow) ? '' : 'style="display: none;"' ?>  ></td>

            <td class="text-right col-xs-2"><button type="button" data-container="#drm_vrac_details_table tbody" data-template="#template_vrac" class="btn btn-xs btn-default dynamic-element-add"><span class="glyphicon glyphicon-plus"></span></button><?php echo getPointAideHtml('drm','mouvements_contrats_ajouter') ?></td>
        </tr>
    </tfoot>
</table>

<script>
    $('.drm_details_tableBody').on('keyup', 'td.volume', $.majSommeLabelBind);

    $("table#drm_vrac_details_table a#type_documents_show").click(function () {
        $("table#drm_vrac_details_table").find(".typedoc_show").each(function () {
            $(this).show();
            var content = $('#template_vrac').html().replace(/style="display: none;"/g,'').replace(/typedoc_unshow\" /g,'typedoc_unshow" style="display: none;"');
        console.log(content);
        $('.modal-body').remove($('script#template_vrac'))
        $('.modal-body').append('<script id="template_vrac" class="template_details" type="text/x-jquery-tmpl">'+content+'<//script>');

        });
        $("table#drm_vrac_details_table").find(".typedoc_unshow").each(function () {
            $(this).hide();
        });

    });



</script>
