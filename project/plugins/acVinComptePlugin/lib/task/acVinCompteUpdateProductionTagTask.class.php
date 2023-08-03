<?php

class acVinCompteUpdateProductionTagTask extends sfBaseTask {

    protected $debug = false;
    protected static $accent_matching = array('accent' => array('Ô','ô', 'Â','â','ê','è','é','É', 'È','Ê','û','Û','î','Î'), 'to_replace' => array('o','o', 'A','a','e','e','e','e','e','e','u','u','i','i'));

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('campagne', null, sfCommandOption::PARAMETER_OPTIONAL, 'Campagne', null),
            new sfCommandOption('reinitialisation_tags_produit', null, sfCommandOption::PARAMETER_REQUIRED, 'Reset tags', false),
            new sfCommandOption('reinitialisation_tags_export', null, sfCommandOption::PARAMETER_REQUIRED, 'Reset tags', false),
            new sfCommandOption('reinitialisation_tags_domaines', null, sfCommandOption::PARAMETER_REQUIRED, 'Reset tags', false),
            new sfCommandOption('debug', null, sfCommandOption::PARAMETER_OPTIONAL, 'use only one code creation', '0'),
        ));

        $this->namespace = 'compte';
        $this->name = 'updateProductionTag';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $context = sfContext::createInstance($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->debug = array_key_exists('debug', $options) && $options['debug'];


        if (!isset($options['campagne']) || !$options['campagne']) {
            $campagne = ConfigurationClient::getInstance()->getCurrentCampagne();
        } else {
            $campagne = $options['campagne'];
        }

        $campagnes = [ConfigurationClient::getInstance()->getCampagneVinicole()->getPrevious($campagne), $campagne];

        $this->logSection("campagnes use", implode(', ', $campagnes));

        $ds = [];
        $factures = [];
        foreach($campagnes as $campagne) {
            $periode = explode("-", $campagne)[0];
            foreach(ArchivageAllView::getInstance()->getByTypeAndCampagne("DS", ConfigurationClient::getInstance()->getCampagneVinicole()->getPrevious($campagne)) as $row) {
                $id = explode("-", $row->id);
                $ds[$id[1]][$id[2]] = $id[2];
            }
            foreach(ArchivageAllView::getInstance()->getByTypeAndCampagne("Facture", $periode) as $row) {
                $id = explode("-", $row->id);
                $factures[$id[1]][$periode] = $periode;
            }
        }

        foreach (EtablissementAllView::getInstance()->findByInterproStatutAndFamilleVIEW('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, null) as $e) {
            $id = $e->key[EtablissementAllView::KEY_ETABLISSEMENT_ID];
            $identifiant = $e->key[EtablissementAllView::KEY_IDENTIFIANT];
            $societeIdentifiant = str_replace("SOCIETE-", "", $e->key[EtablissementAllView::KEY_SOCIETE_ID]);
            $cvi = $e->key[EtablissementAllView::KEY_CVI];
            $tags = array('export' => array(), 'produit' => array(), 'domaines' => array(), 'documents' => array());

            foreach($campagnes as $c) {
                $periode = explode("-", $c)[0];
                $mvts = SV12MouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($id, $c);
                foreach ($mvts as $m) {
                    $produit_libelle = $this->getProduitLibelle($m->produit_hash);
                    if(!$produit_libelle) {
                        continue;
                    }
                    $tags['produit'][$produit_libelle] = 1;
                    $tags['documents']['SV12'.$c] = 1;
                }
                $mvts = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndCampagne($id, $c);
                foreach ($mvts as $m) {
                    $produit_libelle = $this->getProduitLibelle($m->produit_hash);
                    if(!$produit_libelle) {
                        continue;
                    }
                    $tags['produit'][$produit_libelle] = 1;
                    $tags['documents']['DRM '.$c] = 1;
                    if ($m->detail_libelle && preg_match("/export.*_details/", $m->type_hash)) {
                        $tags['export'][$this->replaceAccents($m->detail_libelle)] = 1;
                    }
                }
                if($cvi && $dr = acCouchdbManager::getClient()->find('DR-'.$cvi.'-'.$periode, acCouchdbClient::HYDRATE_JSON)) {
                    $tags['documents']['DR '.$periode] = 1;
                    if(isset($dr->famille_calculee)) {
                        $tags['documents']['DR '.$dr->famille_calculee] = 1;
                    }
                }
                if($identifiant && acCouchdbManager::getClient()->find('SV11-'.$identifiant.'-'.$periode, acCouchdbClient::HYDRATE_JSON)) {
                    $tags['documents']['SV11 '.$periode] = 1;
                }
                if($identifiant && acCouchdbManager::getClient()->find('SV12-'.$identifiant.'-'.$periode, acCouchdbClient::HYDRATE_JSON)) {
                    $tags['documents']['SV12 '.$periode] = 1;
                }
                if($cvi && acCouchdbManager::getClient()->find('SV11-'.$cvi.'-'.$periode, acCouchdbClient::HYDRATE_JSON)) {
                    $tags['documents']['SV11 '.$periode] = 1;
                }
                if($cvi && acCouchdbManager::getClient()->find('SV12-'.$cvi.'-'.$periode, acCouchdbClient::HYDRATE_JSON)) {
                    $tags['documents']['SV12 '.$periode] = 1;
                }
                if($identifiant && isset($ds[$identifiant])) {
                    foreach($ds[$identifiant] as $dsPeriode) {
                        $tags['documents']['DS '.$dsPeriode] = 1;
                    }
                }
                if($cvi && isset($ds[$cvi])) {
                    foreach($ds[$cvi] as $dsPeriode) {
                        $tags['documents']['DS '.$dsPeriode] = 1;
                    }
                }
                if($societeIdentifiant && isset($factures[$societeIdentifiant])) {
                    foreach($factures[$societeIdentifiant] as $facturePeriode) {
                        $tags['documents']['Facture '.$facturePeriode] = 1;
                    }
                }
            }

            $contratDomaines = VracDomainesView::getInstance()->findDomainesByVendeur(str_replace('ETABLISSEMENT-', '', $id), date('Y'), 1000);
            foreach ($contratDomaines->rows as $domaineView) {
                $domaine = $this->replaceAccents($domaineView->key[VracDomainesView::KEY_DOMAINE]);
                $tags['domaines'][$domaine] = 1;
            }

            $etablissement = EtablissementClient::getInstance()->findByIdentifiant(str_replace('ETABLISSEMENT-', '', $id));
            $compte = $etablissement->getContact();
            $compte->tags->remove('export');
            $compte->tags->remove('produit');
            $compte->tags->remove('domaines');
            $compte->tags->remove('documents');
            $compte->tags->remove('droits');

            if (!count($tags)) {
                continue;
            }
            foreach ($tags as $type => $ttags) {
                foreach ($ttags as $t => $null) {
                    $compte->addTag($type, $t);
                }
            }

            if(!$compte){
               echo ("compte de l'établissement $etablissement->_id non trouvé\n");
               continue;
            }

            try {

                $compte->save();
            } catch (Exception $exc) {
                echo $exc."\n";
                continue;
            }

            $this->logSection("done", $compte->identifiant);
        }
    }

    public function getProduitLibelle($hash) {
        $configuration = ConfigurationClient::getInstance()->getCurrent();

        $hash = preg_replace('|^(.*)/details[a-zA-Z0-9]*/[a-zA-Z0-9]+$|', '\1', $hash);

        if(!$configuration->exist($hash)) {
            echo "Hash non trouvé :".$hash."\n";
            return null;
        }


        return $this->replaceAccents($configuration->get($hash)->getLibelleFormat(null, "%format_libelle%"));
    }

    protected function replaceAccents($s) {
        return str_replace(self::$accent_matching['accent'], self::$accent_matching['to_replace'], $s);
    }

}
