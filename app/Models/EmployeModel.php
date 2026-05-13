<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table            = 'employes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nom', 'prenom', 'email', 'password', 
        'role', 'departement_id', 'date_embauche', 'actif'
    ];

    // Dates
    protected $useTimestamps = false;
}