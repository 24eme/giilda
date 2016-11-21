<?php use_helper('PointsAides'); ?>
<?php
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>

<table id="drm_creationvrac_details_table" class="table table-striped">
    <thead>
        <tr>
            <th class="col-xs-4">Acheteur</th>
            <th class="col-xs-2">Volume<?php echo getPointAideHtml('drm','mouvements_contrats_doc_accompagnement_num') ?></th>
            <th class="col-xs-2">Prix<?php echo getPointAideHtml('drm','mouvements_contrats_type_doc') ?></th>
            <th class="col-xs-2">Numéro contrat<?php echo getPointAideHtml('drm','mouvements_contrats_numero') ?></th>

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

            <td class="col-xs-4" ></td>
            <td class="col-xs-2" ></td>
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
$('.drm_details_tableBody').on('keyup','td.volume', $.majSommeLabelBind);
</script>
