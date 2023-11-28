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
 * acVinComptePlugin validator.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class ValidatorCompteLoginFirst extends sfValidatorBase 
{

    public function configure($options = array(), $messages = array()) 
    {
        $this->setMessage('invalid', 'Login interprofessionnel ou code de création invalide.<br/><br/>Vérifiez que :<ul><li>vous chercher à créer un <strong>compte interprofessionnel</strong> (et non un compte ODG/syndical)</li><li>l\'identifiant interprofessionnel est bien exactement le même que celui fourni (notamment contenant autant de 0)</li><li>le code de création correspond à ce que votre interprofession vous a fourni</li></ul><br/><br/>N\'hésitez pas à contacter votre interprofession si après ces vérifications le problème persiste.');
    }

    protected function doClean($values) 
    {
        if (!$values['login'] || !$values['mdp']) {
            return array_merge($values);
        }
        
        $compte = CompteClient::getInstance()->findByLogin($values['login']);

        if (!$compte) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('mdp') => new sfValidatorError($this, 'invalid')));
        }
                
        if ($compte->getStatutTeledeclarant() != CompteClient::STATUT_TELEDECLARANT_NOUVEAU){
            throw new sfValidatorErrorSchema($this, array($this->getOption('mdp') => new sfValidatorError($this, 'invalid')));
        }
        
        if ($compte->mot_de_passe != '{TEXT}' . $values['mdp']) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('mdp') => new sfValidatorError($this, 'invalid')));
        }
            
        return array_merge($values, array('compte' => $compte));
    }
}