<?php

class drm_vrac_detailsActions extends drmGeneriqueActions
{

    public function executeProduit(sfWebRequest $request) {

        return $this->processProduitDetails($request, "DRMDetailVracForm");
    }
}
