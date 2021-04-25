<?php

namespace App\Taxes;

class Detector
{
    protected $seuil;
    public function __construct($seuil)
    {
        $this->seuil = $seuil;
    }
    public function detect(int $montant): bool
    {
        if ($montant > $this->seuil) {
            return true;
        } else {
            return false;
        }
    }
}
