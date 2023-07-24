<?php if(sfConfig::get('app_extra_service_url_documents')): ?>
    <li><a tabindex="-1" href="<?php echo sfConfig::get('app_extra_service_url')['documents'] ?>">Docs ↗</a></li>
<?php endif; ?>
<?php if(sfConfig::get('app_extra_service_url_meteo')): ?>
    <li><a tabindex="-1" href="<?php echo sfConfig::get('app_extra_service_url_meteo'); ?>">Météo ↗</a></li>
<?php endif; ?>
