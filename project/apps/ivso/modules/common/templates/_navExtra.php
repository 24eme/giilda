<?php if(sfConfig::get('app_extra_service_url_documents')): ?>
    <li><a tabindex="-1" href="<?php echo sfConfig::get('app_extra_service_url_documents') ?>">Docs <small>↗</small></a></li>
<?php endif; ?>
<?php if(sfConfig::get('app_extra_service_url_meteo')): ?>
    <li><a tabindex="-1" target="_blank" class="external" href="<?php echo sfConfig::get('app_extra_service_url_meteo'); ?>">Météo <small>↗</small></a></li>
<?php endif; ?>
