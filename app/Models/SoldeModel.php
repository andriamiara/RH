<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\EmployeModel;
use App\Models\TypeCongeModel;

class SoldeModel extends Model
{
    protected $table            = 'soldes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'employe_id', 'type_conge_id', 'annee', 
        'jours_attribues', 'jours_pris'
    ];

    /**
     * Calcule dynamiquement le solde restant (attribues - pris)
     */
    public function getSoldeRestant($employe_id, $type_conge_id, $annee)
    {
        $result = $this->where([
            'employe_id' => $employe_id,
            'type_conge_id' => $type_conge_id,
            'annee' => $annee
        ])->first();

        return $result ? ($result['jours_attribues'] - $result['jours_pris']) : 0;
    }
}