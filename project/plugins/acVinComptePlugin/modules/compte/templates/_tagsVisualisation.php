<div class="row">
    <div class="col-xs-12">
        <strong>Tags :</strong>
        <?php foreach ($compte->tags as $type_tag => $selected_tags) : ?>
            <?php foreach ($selected_tags as $t): ?>
                <?php $targs['tags'] = implode(',', array($type_tag . ':' . $t)); ?>
                <span class="btn-group"><a href="" title="<?php echo ucfirst($type_tag) ?>" class="btn <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?> btn-default active"><?php echo substr(strtoupper($type_tag), 0, 1); ?></a><a class="btn btn-default <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?>" href="<?php echo url_for('compte_search', $targs) ?>"><?php echo str_replace('_', ' ', $t) ?></a>
                    <?php $targs['tag'] = $t; ?>
                    <?php $targs['q'] = $compte->identifiant ?>
                        <?php if ($type_tag == 'manuel'): ?><a class="btn btn-default <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?>" href="<?php echo url_for('compte_removetag', $targs) ?>"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
                </span>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <a class="<?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?> btn-default" href="<?php echo url_for('compte_search', array('q' => $compte->identifiant)) ?>"><span class="glyphicon glyphicon-plus"></span></a>
    </div>
</div>
