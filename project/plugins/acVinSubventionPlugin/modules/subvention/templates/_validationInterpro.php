<div class="row row-condensed2">
	<div class="col-xs-12">
		<form class="form-horizontal" method="POST" action="">

		<?php echo $form->renderGlobalErrors(); ?>
		<?php echo $form->renderHiddenFields(); ?>
		<?php foreach($form as $categorie => $items): ?>
			<div class="panel panel-default">
			<?php if($items instanceof sfFormFieldSchema): ?>
					<div class="panel-heading">
						<h3 style="margin-bottom: 20px; margin-top: 15px;">Approuver le dossier <?php echo $subvention->approbations->get($categorie)->getLibelle() ?></h3>
					</div>
					<div class="panel-body">
						<?php foreach($items as $key => $item): ?>
							<div class="form-group">
								 <?php echo $item->renderError(); ?>
								 <?php echo $item->renderLabel(null, array("class" => "col-sm-3 control-label")); ?>
								 <div class="<?php if(get_class($item->getWidget()) == "bsWidgetFormInputFloat"): ?>col-sm-3<?php else: ?>col-sm-4<?php endif;?>">
											<?php $unite = $subvention->approbations->get($categorie)->getSchemaItem($key, "unite") ?>
											<?php if($unite): ?><div class="input-group"><?php endif ?>
											<?php echo $item->render(); ?>
											<?php if($unite): ?>
													<span class="input-group-addon"><?php echo $unite; ?></span>
													</div>
											<?php endif; ?>
								 </div>
								 <div class="col-sm-3">
										 <?php echo $item->renderHelp(); ?>
								 </div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</form>
	</div>
</div>
