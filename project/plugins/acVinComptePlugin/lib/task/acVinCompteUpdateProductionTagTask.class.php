<?php

/* This file is part of the acVinComptePlugin package.
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
 * acVinComptePlugin task.
 *
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinCompteUpdateProductionTagTask extends sfBaseTask {

    protected $debug = false;
    protected static $accent_matching = array('accent' => array('Ô','ô', 'Â','â','ê','è','é','É', 'È','Ê','û','Û','î','Î'), 'to_replace' => array('o','o', 'A','a','e','e','e','e','e','e','u','u','i','i'));

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declaration'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
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

        $campagnes = [];
        $currentCampagne = ConfigurationClient::getInstance()->getCampagneVinicole()->getCurrent();
        for($i=0; $i < 3; $i++) {
            $campagnes[$currentCampagne] = $currentCampagne;
            $currentCampagne = ConfigurationClient::getInstance()->getCampagneVinicole()->getPrevious($currentCampagne);
        }

        $this->logSection("campagne use", implode(',', $campagnes));

        foreach (EtablissementAllView::getInstance()->findByInterproStatutAndFamilleVIEW('INTERPRO-declaration', EtablissementClient::STATUT_ACTIF, null) as $e) {
            $id = $e->key[EtablissementAllView::KEY_ETABLISSEMENT_ID];
            $tags = array('export' => array(), 'produit' => array(), 'domaines' => array(), 'documents' => array());
            $etablissement = EtablissementClient::getInstance()->findByIdentifiant(str_replace('ETABLISSEMENT-', '', $id));
            if (!$etablissement) {
                throw new sfException("etablissement $id non trouvé");
            }
            $compte = $etablissement->getContact();
            if (class_exists('DeclarationIdentifiantView')) {
                $types_view = DeclarationIdentifiantView::getInstance()->getByIdentifiant($etablissement->identifiant);
                foreach($types_view->rows as $t) {
                    $docCampagne = $t->key[DeclarationIdentifiantView::KEY_CAMPAGNE];

                    if(strlen($docCampagne) == 4) {
                        $docCampagne = $docCampagne.'-'.($docCampagne + 1);
                    }
                    if (in_array($docCampagne, $campagnes)) {
                        $tags['documents'][$t->key[DeclarationIdentifiantView::KEY_TYPE].'_'.explode("-", $docCampagne)[0]] = 1;
                    }
                }
            }

            if (class_exists('MouvementLotView')) {
                foreach(MouvementLotView::getInstance()->getByIdentifiant($etablissement->identifiant)->rows as $row) {
                    if (in_array($row->value->campagne, $campagnes) && $row->value->affectable) {
                        $tags['documents']['controle_odg_'.explode("-", $row->value->campagne)[0]] = 1;
                    }
                }
            }
            $compte->tags->remove('documents');

            $this->logSection("reset tags documents", $compte->identifiant);

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
