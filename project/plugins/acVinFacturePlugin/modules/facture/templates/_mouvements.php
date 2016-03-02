<?php
use_helper('Float');
use_helper('Date');
use_helper('Prix');
?>
<div class="row row-margin">
    <div class="col-xs-8">
        <h2 class="vertical-center" style="margin: 0 0 20px 0;">Mouvements en attente de facturation</h2>
    </div>
    <div class="col-xs-4 text-right">
        <a href="<?php echo url_for('facture_creation', array('identifiant' => $societe->identifiant));?>" class="btn btn-warning"><span class="glyphicon glyphicon-save-file"></span> Facturer les mouvements</a>
    </div>
</div>

<?php if (!count($mouvements)) : ?>
    <div class="row row-margin">
        <p class="text-center text-muted">Pas de mouvements en attente de facturation</p>
    </div>
<?php else : ?>
    <div class="row row-margin">
        <div class="col-xs-12">
        	
    
	    	<table class="table table-striped">
			    <thead>
			        <tr>
			            <th>Document</th>
			            <th>Produits</th>   
			            <th>Type</th>
			            <th style="width: 90px;">Quantité</th>
			            <th style="width: 90px;">Prix TTC</th>
			        </tr>
			    </thead>
			    <tbody>
                	<?php foreach ($mouvements as $mouvement): ?>
			    	<tr class="vertical-center">
			    		<td class="text-left">
			    			<?php
                            $numeroFormatted = (strstr($mouvement->numero, 'DRM') !== false) ? DRMClient::getInstance()->getLibelleFromId($mouvement->numero) :
                                    $mouvement->nom_facture;

                            echo link_to($numeroFormatted, 'facture_redirect_to_doc', array('iddocument' => $mouvement->numero));
                            ?>
			    		</td>
			    		<td class="text-left"><?php echo $mouvement->produit_libelle ?></td>
			    		<td class="text-left"><?php echo $mouvement->type_libelle ?></td>
			    		<td class="text-right"><?php echoFloat($mouvement->volume * -1) ; ?> <?php if($mouvement->type_libelle): ?>&nbsp;hl<?php else: ?>&nbsp;&nbsp;&nbsp;<?php endif;?></td>
			    		<td class="text-right"><?php echoTtc($mouvement->prix_ht); ?>&nbsp;&euro;</td>
			    	</tr>
                	<?php endforeach; ?>
			    </tbody>
			</table>
        </div>
    </div>
<?php endif; ?>
   