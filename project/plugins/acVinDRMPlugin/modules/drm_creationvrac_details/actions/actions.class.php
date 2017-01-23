<?php

class drm_creationvrac_detailsActions extends drmGeneriqueActions
{

    public function executeProduit(sfWebRequest $request) {
      return $this->processProduitDetails($request, "DRMDetailCreationVracForm");
    }
}
