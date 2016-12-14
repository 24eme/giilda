<?php

/* This file is part of the DAIDSPlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * StatistiqueRouting configuration.
 * 
 * @package    StatistiqueRouting
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class StatistiqueRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        $r->prependRoute('statistiques', new sfRoute('/recherche', array('module' => 'statistique', 'action' => 'index')));
        $r->prependRoute('statistiques_drm', new sfRoute('/recherche/drm', array('module' => 'statistique', 'action' => 'drmStatistiques')));
        $r->prependRoute('statistiques_vrac', new sfRoute('/recherche/vrac', array('module' => 'statistique', 'action' => 'vracStatistiques')));
        $r->prependRoute('statistiques_vrac_csv', new sfRoute('/recherche/vrac/csv', array('module' => 'statistique', 'action' => 'vracCsvStatistiques')));
        $r->prependRoute('statistiques_drm_csv', new sfRoute('/recherche/drm/csv', array('module' => 'statistique', 'action' => 'drmCsvStatistiques')));
        $r->prependRoute('statistiques_stats', new sfRoute('/recherche/stats', array('module' => 'statistique', 'action' => 'statsStatistiques')));
    }

}
