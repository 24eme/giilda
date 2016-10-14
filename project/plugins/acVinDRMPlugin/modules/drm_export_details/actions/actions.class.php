<?php

class drm_export_detailsActions extends drmGeneriqueActions
{

    public function executeProduit(sfWebRequest $request) {

        return $this->processProduitDetails($request, "DRMDetailExportForm");
    }
}
