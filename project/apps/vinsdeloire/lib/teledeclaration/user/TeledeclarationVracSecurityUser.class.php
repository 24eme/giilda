<?php

abstract class TeledeclarationVracSecurityUser extends TeledeclarationSecurityUser
{
    protected $_vrac = null;

    /**
     *
     * @param sfEventDispatcher $dispatcher
     * @param sfStorage $storage
     * @param type $options 
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
    {
        parent::initialize($dispatcher, $storage, $options);

        if (!$this->isAuthenticated()) {
            $this->signOutDeclaration();
        }
    }

    /**
     * 
     */
    protected function clearCredentials()
    {
        
    }

    /**
     * 
     */
    public function signOutDeclaration()
    {
        $this->_vrac = null;
        $this->clearCredentials();
    }

    /**
     * @return DR
     */
    public function getVrac()
    {
        $this->requireVrac();
        $this->requireTiers();
        if (is_null($this->_vrac)) {
            $this->_vrac = $this->getDeclarant()->getVrac($this->getCampagne());
            if (!$this->_vrac) {
                $vrac = new VRAC();
                $vrac->set('_id', 'Vrac-' . $this->getDeclarant()->identifiant . '-' . $this->getCampagne());
                return $vrac;
            }
        }

        return $this->_declaration;
    }

    /**
     * @return string
     */
    public function getCampagne()
    {
        return "2012";
    }

}
