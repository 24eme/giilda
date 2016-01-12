<div class="row">
    <div class="<?php if (isset($smallBlock)): ?>col-xs-12 <?php else: ?>col-xs-6 <?php endif; ?> <?php if (isset($smallBlock)): ?>text-center<?php endif; ?>">
        <address style="margin-bottom: 5px;" class="<?php if (!isset($smallBlock)): ?>lead<?php endif ?>">
        <?php echo $compte->adresse; ?><br />
        <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
        <?php echo $compte->code_postal; ?> <?php echo $compte->commune; ?> <small class="text-muted">(<?php echo $compte->pays; ?>)</small>
        </address>
    </div>

    <div class="<?php if (isset($smallBlock)): ?>col-xs-12 <?php else: ?>col-xs-6 <?php endif; ?> <?php if (isset($smallBlock)): ?>text-left<?php endif; ?>">
        <ul class="list-unstyled" style="margin-bottom: 5px;">
            <?php if ($compte->email) : ?>
            <li><strong>Email : </strong><a href="mailto:<?php echo $compte->email; ?>"><?php echo $compte->email; ?></a></li>
            <?php endif; ?>
            <?php if ($compte->telephone_perso) : ?>
            <li><strong>Tél. perso : </strong><a href="callto:<?php echo $compte->telephone_perso; ?>"><?php echo $compte->telephone_perso; ?></a></li>
            <?php endif; ?>
            <?php if ($compte->telephone_bureau) : ?>
            <li><strong>Tél. bureau : </strong><a href="callto:<?php echo $compte->telephone_bureau; ?>"><?php echo $compte->telephone_bureau; ?></a></li>
            <?php endif; ?>
            <?php if ($compte->telephone_mobile) : ?>
            <li><strong>Tél. mobile : </strong><a href="callto:<?php echo $compte->telephone_mobile; ?>"><?php echo $compte->telephone_mobile; ?></a></li>
            <?php endif; ?>
            <?php if ($compte->fax) : ?>
            <li><strong>Fax : </strong><a href="callto:<?php echo $compte->fax; ?>"><?php echo $compte->fax; ?></a></li>
            <?php endif; ?>
            <?php if ($compte->exist('site_internet') && $compte->site_internet) : ?>
            <li><strong>Site Internet : </strong><a href="<?php echo $compte->site_internet; ?>"><?php echo $compte->site_internet; ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="col-xs-12">
        <strong>Tags :</strong>
        <?php foreach ($compte->tags as $type_tag => $selected_tags) : ?>
            <?php foreach ($selected_tags as $t): ?>
            <?php $targs['tags'] = implode(',',array($type_tag . ':' . $t)); ?>
            <span class="btn-group"><a title="<?php echo ucfirst($type_tag) ?>" class="btn <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?> btn-default disabled"><?php echo substr(strtoupper($type_tag),0,1); ?></a><a class="btn btn-default <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?>" href="<?php echo url_for('compte_search', $targs) ?>"><?php echo str_replace('_', ' ', $t) ?></a>
            <?php $targs['tag'] = $t; ?>
            <?php $targs['q'] = $compte->identifiant ?>
            <?php if ($type_tag == 'manuel'): ?><a class="btn btn-default <?php if (!isset($smallBlock)): ?>btn-sm<?php else: ?>btn-xs<?php endif; ?>" href="<?php echo url_for('compte_removetag', $targs) ?>"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
            </span>
            <?php endforeach; ?>
    <?php endforeach; ?>
    </div>
</div>
