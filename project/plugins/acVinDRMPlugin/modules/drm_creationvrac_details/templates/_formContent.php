<?php use_helper('PointsAides'); ?>
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>

<table id="drm_creationvrac_details_table" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-3">Numéro contrat<?php echo getPointAideHtml('drm','mouvements_contrats_numero') ?></th>
            <th class="col-xs-3">Acheteur</th>

            <th class="col-xs-2">Prix hl.<?php echo getPointAideHtml('drm','mouvements_contrats_type_doc') ?></th>
            <th class="col-xs-2">Vol. enl.<?php echo getPointAideHtml('drm','mouvements_contrats_doc_accompagnement_num') ?></th>


            <th class="col-xs-2"></th>
        </tr>
    </thead>
    <tbody class="drm_details_tableBody">
        <?php
        foreach ($form as $itemForm) {
            if ($itemForm instanceof sfFormFieldSchema) {
                include_partial('item', array('form' => $itemForm, 'detail' => $detail, 'isTeledeclarationMode' => $isTeledeclarationMode));
            } else {
                $itemForm->renderRow();
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>

            <td class="col-xs-3" ></td>
            <td class="col-xs-3" ></td>
            <td class="col-xs-2" ></td>
            <td class="lead text-right col-xs-2">
                <div class="input-group">
                    <div class="input-group-addon">&Sigma;</div>
                    <input type="text" class="form-control text-right drm_details_volume_total" tabindex="-1" readonly="readonly" value="<?php echo $detail->get($catKey)->get($key) > 0 ? $detail->get($catKey)->get($key) : "0.00" ?>" />
                    <div class="input-group-addon">hl</div>
                </div>
            </td>
            <td class="text-right col-xs-2"><a data-container="#drm_creationvrac_details_table tbody" data-template="#template_creationvrac" class="btn btn-xs btn-default dynamic-element-add"><span class="glyphicon glyphicon-plus"></span></a><?php echo getPointAideHtml('drm','mouvements_contrats_ajouter') ?></td>
        </tr>
    </tfoot>
</table>

<script>
    $('.drm_details_tableBody').on('keyup', 'td.volume', $.majSommeLabelBind);

    $("table#drm_creationvrac_details_table a#type_documents_show").click(function () {
        $("table#drm_creationvrac_details_table").find(".typedoc_show").each(function () {
            $(this).show();
            var content = $('#template_vrac').html().replace(/style="display: none;"/g,'').replace(/typedoc_unshow\" /g,'typedoc_unshow" style="display: none;"');
        console.log(content);
        $('.modal-body').remove($('script#template_vrac'))
        $('.modal-body').append('<script id="template_vrac" class="template_details" type="text/x-jquery-tmpl">'+content+'<//script>');
        });
    });



</script>
