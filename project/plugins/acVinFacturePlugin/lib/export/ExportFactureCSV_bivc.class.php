<?php

class ExportFactureCSV_bivc extends ExportFactureCSV_ivso {

    public function getMontant($montant, $sens) {
        if($montant < 0) {

            return $montant * -1;
        }

        return $montant;
    }

    public function getSens($montant, $sens) {
        if($montant < 0 && $sens == "CREDIT") {

            return "DEBIT";
        }

        if($montant < 0 && $sens == "DEBIT") {

            return "CREDIT";
        }

        return $sens;
    }
}
