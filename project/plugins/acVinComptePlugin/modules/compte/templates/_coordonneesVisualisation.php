<div class="row">
    <div class="col-xs-6">
        <address class="lead">
        <?php echo $compte->adresse; ?><br />
        <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
        <?php echo $compte->code_postal; ?> <?php echo $compte->commune; ?> <small class="text-muted">(<?php echo $compte->pays; ?>)</small>
        </address>
    </div>

    <div class="col-xs-6">
        <dl class="dl-horizontal">
            <?php if ($compte->email) : ?>
            <dt>Email :</dt><dd><a href="mailto:<?php echo $compte->email; ?>"><?php echo $compte->email; ?></a></dd>
            <?php endif; ?>
            <?php if ($compte->telephone_perso) : ?>
            <dt>Tél. perso :</dt><dd><a href="callto:<?php echo $compte->telephone_perso; ?>"><?php echo $compte->telephone_perso; ?></a></dd>
            <?php endif; ?>
            <?php if ($compte->telephone_bureau) : ?>
            <dt>Tél. bureau :</dt><dd><a href="callto:<?php echo $compte->telephone_bureau; ?>"><?php echo $compte->telephone_bureau; ?></a></dd>
            <?php endif; ?>
            <?php if ($compte->telephone_mobile) : ?>
            <dt>Tél. mobile :</dt><dd><a href="callto:<?php echo $compte->telephone_mobile; ?>"><?php echo $compte->telephone_mobile; ?></a></dd>
            <?php endif; ?>
            <?php if ($compte->fax) : ?>
            <dt>Fax :</dt><dd><a href="callto:<?php echo $compte->fax; ?>"><?php echo $compte->fax; ?></a></dd>
            <?php endif; ?>
            <?php if ($compte->exist('site_internet') && $compte->site_internet) : ?>
            <dt>Site Internet :</dt><dd><a href="<?php echo $compte->site_internet; ?>"><?php echo $compte->site_internet; ?></a></dd>
            <?php endif; ?>
        </dl>
    </div>

    <div class="col-xs-12">
        <strong>Tags :</strong>
        <?php foreach ($compte->tags as $type_tag => $selected_tags) : ?>
            <?php foreach ($selected_tags as $t): ?>
            <?php $targs['tags'] = implode(',',array($type_tag . ':' . $t)); ?>
            <span class="btn-group"><a title="<?php echo ucfirst($type_tag) ?>" class="btn btn-sm btn-default disabled"><?php echo substr(strtoupper($type_tag),0,1); ?></a><a class="btn btn-default btn-sm" href="<?php echo url_for('compte_search', $targs) ?>"><?php echo str_replace('_', ' ', $t) ?></a>
            <?php $targs['tag'] = $t; ?>
            <?php $targs['q'] = $compte->identifiant ?>
            <?php if ($type_tag == 'manuel'): ?><a class="btn btn-default btn-sm" href="<?php echo url_for('compte_removetag', $targs) ?>"><span class="glyphicon glyphicon-trash"></span></a><?php endif; ?>
            </span>
            <?php endforeach; ?>
    <?php endforeach; ?>
    </div>
</div>
