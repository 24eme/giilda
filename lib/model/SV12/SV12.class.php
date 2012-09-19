<?php

/**
 * Model for Vrac
 *
 */
class SV12 extends BaseSV12 implements InterfaceMouvementDocument, InterfaceVersionDocument, InterfaceDeclarant {

    protected $mouvement_document = null;
    protected $version_document = null;
    protected $declarant = null;

    public function  __construct() {
        parent::__construct();   
        $this->mouvement_document = new MouvementDocument($this);
        $this->version_document = new VersionDocument($this);
        $this->declarant = new Declarant($this);
    }

    public function constructId() {
        $this->valide->statut = SV12Client::SV12_STATUT_BROUILLON;
        $this->campagne = '2012-2013';
        $this->set('_id', SV12Client::getInstance()->buildId($this->identifiant, 
                                                            $this->periode, 
                                                            $this->version));
    }

    public function getPeriodeAndVersion() {

        return SV12Client::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function storeContrats() {
        $contratsView = SV12Client::getInstance()->findContratsByEtablissement($this->identifiant);
        foreach ($contratsView as $contratView)
        {
            $idContrat = preg_replace('/VRAC-/', '', $contratView->value[VracClient::VRAC_VIEW_NUMCONTRAT]);
            $this->updateContrat($idContrat,$contratView->value);
        }
    }
    
       
    
    public function update($params = array()) {
        
    }

    public function isValidee() {

        return ($this->valide->date_saisie) && ($this->valide->statut==SV12Client::SV12_STATUT_VALIDE);
    }

    public function isBrouillon() {

        return ($this->valide->date_saisie) && ($this->valide->statut==SV12Client::SV12_STATUT_BROUILLON);
    }
    
    public function updateContrat($num_contrat, $contrat) {
        $founded = false;
        foreach ($this->contrats as $c) {
            if ($c->contrat_numero == $num_contrat) {
                break;
            }
        }
        if (!$founded) {
            if (!$contrat) {
                throw new acCouchdbException(sprintf("Le Contrat \"%s\" n'existe pas!", $num_contrat));
            }
            $contratObj = new stdClass();
            $contratObj->contrat_numero = $num_contrat;
            $contratObj->contrat_type = $contrat[VracClient::VRAC_VIEW_TYPEPRODUIT];
            $contratObj->produit_libelle = ConfigurationClient::getCurrent()->get($contrat[VracClient::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat(array(), "%g% %a% %m% %l% %co% %ce% %la%");
            $contratObj->produit_hash = $contrat[VracClient::VRAC_VIEW_PRODUIT_ID];
            $contratObj->vendeur_identifiant = $contrat[VracClient::VRAC_VIEW_VENDEUR_ID];
            $contratObj->vendeur_nom = $contrat[VracClient::VRAC_VIEW_VENDEUR_NOM];
            $contratObj->volume_prop = $contrat[VracClient::VRAC_VIEW_VOLPROP];
            $this->contrats->add($num_contrat, $contratObj);
        }
    }

    public function updateTotaux() {
        $this->remove('totaux');
        $this->add('totaux');

        $this->totaux->volume_raisins = 0;
        $this->totaux->volume_mouts = 0;

        foreach ($this->contrats as $contrat) {
            if(!$this->totaux->produits->exist($contrat->produit_hash)) {
                $noeud = $this->totaux->produits->add($contrat->produit_libelle);
                $noeud->produit_hash = $contrat->produit_hash;
                $noeud->volume_raisins = 0;      
                $noeud->volume_mouts = 0;      
            } else {
                $noeud = $this->totaux->produits->get($contrat->produit_libelle);
            }

            if ($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS) {
                $noeud->volume_raisins += $contrat->volume; 
                $this->totaux->volume_raisins += $contrat->volume; 
            } elseif($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
                $noeud->volume_mouts += $contrat->volume;
                $this->totaux->volume_mouts += $contrat->volume;   
            }
        }
    }
    
    public function updateVolume($num_contrat,$volume) {
        $this->contrats[$num_contrat]->volume = $volume;
    }

    public function validate() {
        $this->valide->date_saisie = date('d-m-y');
        $this->valide->statut = SV12Client::SV12_STATUT_VALIDE;

        $this->generateMouvements();
        $this->updateTotaux();
    }

    public function devalide() {
        $this->clearMouvements();
        $this->valide->date_saisie = '';
        $this->valide->statut = SV12Client::SV12_STATUT_BROUILLON;
    }
    
    public function saveBrouillon() {
        $this->valide->date_saisie = date('d-m-y');
        $this->valide->statut = SV12Client::SV12_STATUT_BROUILLON;
    }
    
    public function getSV12ByProduitsType() {
        $sv12ByProduitsTypes = new stdClass();
        
        $sv12ByProduitsTypes->rows = array();

        $sv12ByProduitsTypes->volume_raisins = 0;
        $sv12ByProduitsTypes->volume_mouts = 0;
        $sv12ByProduitsTypes->volume_total = 0;
        
        foreach ($this->contrats as $contrat) {
            if(isset($sv12ByProduitsTypes->rows[$contrat->produit_hash]))
            {
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins += $contrat->volume;
                    $sv12ByProduitsTypes->volume_raisins+=$contrat->volume;
                }                
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->mouts += $contrat->volume;
                    $sv12ByProduitsTypes->volume_mouts+=$contrat->volume;
                }
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_total += $contrat->volume;
                $sv12ByProduitsTypes->volume_total+=$contrat->volume;
            }
            else
            {
                $sv12ByProduitsTypes->rows[$contrat->produit_hash] = new stdClass();
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->appellation = $contrat->produit_libelle;
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins = $contrat->volume;
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_mouts = 0;
                    $sv12ByProduitsTypes->volume_raisins+=$contrat->volume;
                }
                if($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS)
                {
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_mouts = $contrat->volume;
                    $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_raisins = 0;
                    $sv12ByProduitsTypes->volume_mouts+=$contrat->volume;
                }
                $sv12ByProduitsTypes->rows[$contrat->produit_hash]->volume_total = $contrat->volume;
                $sv12ByProduitsTypes->volume_total+=$contrat->volume;
            }
        }
        return $sv12ByProduitsTypes;
    }

    public function getDate() {

        return date('Y-m-d');
    }

    public function getSuivante() {

        return false;
    }

    /**** VERSION ****/

    public static function buildVersion($rectificative, $modificative) {

        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public function getVersion() {

        return $this->_get('version');
    }

    public function hasVersion() {

        return $this->version_document->hasVersion();
    }

    public function isVersionnable() {
        if (!$this->isValidee()) {
           
           return false;
        }

        return $this->version_document->isVersionnable();
    }

    public function getRectificative() {

        return $this->version_document->getRectificative();
    }

    public function isRectificative() {

        return $this->version_document->isRectificative();
    }

    public function isRectifiable() {
        
        return false;
    }

    public function getModificative() {

        return $this->version_document->getModificative();
    }

    public function isModificative() {

        return $this->version_document->isModificative();
    }

    public function isModifiable() {

        return $this->version_document->isModifiable();
    }

    public function getPreviousVersion() {

       return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        return SV12Client::getInstance()->findMasterRectificative($this->identifiant, 
                                                                 $this->periode, 
                                                                 self::buildVersion($this->getRectificative() - 1, 0));
    }

    public function needNextVersion() {

       return $this->version_document->needNextVersion();      
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return SV12Client::getInstance()->findMaster($this->identifiant, $this->periode);
    }

    public function findDocumentByVersion($version) {

        return SV12Client::getInstance()->find(SV12Client::getInstance()->buildId($this->identifiant, $this->periode, $version));
    }

    public function getMother() {

        return $this->version_document->getMother();   
    }

    public function motherGet($hash) {

        return $this->version_document->motherGet($hash);
    }

    public function motherExist($hash) {

        return $this->version_document->motherExist($hash);
    }

    public function motherHasChanged() {

        return true;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative() {

        return $this->version_document->generateRectificative();
    }

    public function generateModificative() {

        return $this->version_document->generateModificative();
    }

    public function generateNextVersion() {

        return false;
    }

    public function listenerGenerateVersion($document) {
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        
    }

    /**** FIN DE VERSION ****/

    /**** MOUVEMENTS ****/

    public function getMouvements() {

        return $this->_get('mouvements');
    }

    public function getMouvementsCalcule() {
        
        $mouvements = array();
        foreach($this->contrats as $contrat) {
            $mouvement_vendeur = $contrat->getMouvementVendeur();
            if ($mouvement_vendeur) {
                $mouvements[$contrat->getVrac()->vendeur_identifiant][$mouvement_vendeur->getMD5Key()] = $mouvement_vendeur;
            }
            
            $mouvement_acheteur = $contrat->getMouvementAcheteur();
            if ($mouvement_acheteur) {
                $mouvements[$this->getDocument()->identifiant][$mouvement_acheteur->getMD5Key()] = $mouvement_acheteur;
            }
        }

        return $mouvements;
    }

    public function getMouvementsCalculeByIdentifiant($identifiant) {
       
       return $this->mouvement_document->getMouvementsCalculeByIdentifiant($identifiant);
    }
    
    public function generateMouvements() {

        return $this->mouvement_document->generateMouvements();
    }
    
    public function findMouvement($cle){
        
        return $this->mouvement_document->findMouvement($cle);
    }

    public function clearMouvements(){
        $this->remove('mouvements');
        $this->add('mouvements');
    }

    /**** FIN DES MOUVEMENTS ****/
    
    
    /**** DECLARANT ****/
        
    public function storeDeclarant() {
        $this->declarant->storeDeclarant();
    }

    public function getEtablissementObject() {
        return $this->declarant->getEtablissementObject();
    }
    
    /**** FIN DES DECLARANT ****/
    
    public function __toString()
    {
        return SV12Client::getInstance()->getLibelleFromIdSV12($this->_id);
    }
}