<?php if (!$compte->isSameAdresseThanSociete() || isset($forceCoordonnee)): ?>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-3 text-muted">
                Adresse&nbsp;:
            </div>
            <div class="col-xs-9">
                <address style="margin-bottom: 0;">
                    <?php echo $compte->adresse; ?><br />
                    <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
                    <span <?php if($compte->insee): ?>title="<?php echo $compte->insee ?>"<?php endif; ?>><?php echo $compte->code_postal; ?></span> <?php echo $compte->commune; ?> <?php if($compte->pays): ?><small class="text-muted">(<?php echo $compte->pays; ?>)<?php endif; ?></small>
                </address>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (!$compte->isSameContactThanSociete() || isset($forceCoordonnee)): ?>
    <div style="margin-top: 10px;" class="col-xs-12">
        <?php if(count($compte->getEmails())): ?>
            <div class="row">
                <div class="col-xs-3 text-muted">
                    Email&nbsp;:
                </div>
                <div class="col-xs-9">
                    <?php foreach ($compte->getEmails() as $email): ?>
                        <small><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></small>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($compte->getTeledeclarationEmail()): ?>
            <div class="row">
                <div class="col-xs-3 text-muted">
                    ETelecl.&nbsp;:
                </div>
                <div class="col-xs-9">
                    <small><a href="mailto:<?php echo $compte->getTeledeclarationEmail(); ?>"><?php echo $compte->getTeledeclarationEmail(); ?></a></small>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($compte->telephone_perso) : ?>
            <div class="row">
                <div class="col-xs-3 text-muted">
                    Tél.&nbsp;perso&nbsp;:
                </div>
                <div class="col-xs-9">
                    <a href="callto:<?php echo $compte->telephone_perso; ?>"><?php echo $compte->telephone_perso; ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($compte->telephone_bureau) : ?>
            <div class="row">
                <div class="col-xs-3 text-muted" title="Téléphone du bureau" style='overflow: hidden; text-overflow: " :";'>
                    Tél.&nbsp;bureau&nbsp;:
                </div>
                <div class="col-xs-9"><a href="callto:<?php echo $compte->telephone_bureau; ?>"><?php echo $compte->telephone_bureau; ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($compte->telephone_mobile) : ?>
            <div class="row">
                <div class="col-xs-3 text-muted">
                    Tél.&nbsp;mobile&nbsp;:
                </div>
                <div class="col-xs-9">
                    <a href="callto:<?php echo $compte->telephone_mobile; ?>"><?php echo $compte->telephone_mobile; ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($compte->fax) : ?>
            <div class="row">
                <div class="col-xs-3 text-muted">
                    Fax&nbsp;:
                </div>
                <div class="col-xs-9">
                    <a href="callto:<?php echo $compte->fax; ?>"><?php echo $compte->fax; ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($compte->exist('site_internet') && $compte->site_internet) : ?>
            <div class="row">
                <div class="col-xs-3 text-muted" title="Site Internet" style='overflow: hidden; text-overflow: " :";'>
                    Site&nbsp;Internet&nbsp;:
                </div>
                <div class="col-xs-9">
                    <a href="<?php echo "http://".str_replace(array("http://","https://"),array("",""),$compte->site_internet); ?>"><?php echo $compte->site_internet; ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php elseif ($compte->isSameContactThanSociete() && !isset($forceCoordonnee)): ?>
    <?php if($compte->getTeledeclarationEmail()): ?>
        <div style="margin-top: 10px;" class="col-xs-12">
        <div class="row">
            <div class="col-xs-3 text-muted">
                Em.Telecl.&nbsp;:
            </div>
            <div class="col-xs-9">
                <small><a href="mailto:<?php echo $compte->getTeledeclarationEmail(); ?>"><?php echo $compte->getTeledeclarationEmail(); ?></a></small>
            </div>
        </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
