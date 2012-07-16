<?php

class drm_detailsActions extends sfActions
{
  public function executeContrats()
  {
      $this->drm = $this->getRoute()->getDRM();
      $this->form = new DRMDetailForm($this->drm);
      
  }
  
}
