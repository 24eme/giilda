<div class="row">
    <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
        Adresse&nbsp;:
    </div>
    <div style="margin-bottom: 5px" class="col-xs-9">
        <address style="margin-bottom: 0;">
            <?php echo $compte->adresse; ?><br />
            <?php if ($compte->adresse_complementaire) : ?><?php echo $compte->adresse_complementaire ?><br /><?php endif ?>
            <span <?php if($compte->insee): ?>title="<?php echo $compte->insee ?>"<?php endif; ?>><?php echo $compte->code_postal; ?></span> <?php echo $compte->commune; ?> <?php if($compte->pays): ?><small class="text-muted">(<?php echo $compte->pays; ?>)</small><?php endif; ?>
        </address>
    </div>
</div>
<?php if ($compte->email) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Email<?php if(count($compte->getEmails()) > 1 ): ?>s<?php endif; ?> :
        </div>

            <div style="margin-bottom: 5px" class="col-xs-9">
                <?php foreach ($compte->getEmails() as $email): ?>
                    <small><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></small><br/>
                <?php endforeach; ?>
            </div>
    </div>
<?php endif; ?>
<?php if ($compte->telephone_perso) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Tél. perso :
        </div>
        <div style="margin-bottom: 5px" class="col-xs-9">
            <a href="callto:<?php echo $compte->telephone_perso; ?>"><?php echo $compte->telephone_perso; ?></a>
        </div>
    </div>
<?php endif; ?>
<?php if ($compte->telephone_bureau) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Tél.&nbsp;bureau&nbsp;:
        </div>
        <div style="margin-bottom: 5px" class="col-xs-9"><a href="callto:<?php echo $compte->telephone_bureau; ?>"><?php echo $compte->telephone_bureau; ?></a>
        </div>
    </div>
<?php endif; ?>
<?php if ($compte->telephone_mobile) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Tél.&nbsp;mobile&nbsp;:
        </div>
        <div style="margin-bottom: 5px" class="col-xs-9">
            <a href="callto:<?php echo $compte->telephone_mobile; ?>"><?php echo $compte->telephone_mobile; ?></a>
        </div>
    </div>
<?php endif; ?>
<?php if ($compte->fax) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Fax&nbsp;:
        </div>
        <div style="margin-bottom: 5px" class="col-xs-9">
            <a href="callto:<?php echo $compte->fax; ?>"><?php echo $compte->fax; ?></a>
        </div>
    </div>
<?php endif; ?>
<?php if ($compte->exist('site_internet') && $compte->site_internet) : ?>
    <div class="row">
        <div style="margin-bottom: 5px;" class="col-xs-3 text-muted">
            Site&nbsp;Internet&nbsp;:
        </div>
        <div style="margin-bottom: 5px" class="col-xs-9">
            <a href="<?php echo "http://".str_replace(array("http://","https://"),array("",""),$compte->site_internet); ?>"><?php echo $compte->site_internet; ?></a>
        </div>
    </div>
<?php endif; ?>
