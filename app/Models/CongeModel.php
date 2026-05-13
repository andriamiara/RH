<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table            = 'conges';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // On ne garde que created_at selon l'image

    protected $allowedFields    = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin', 
        'nb_jours', 'motif', 'statut', 'commentaire_rh', 'traite_par'
    ];

    // Vous pourriez ajouter une méthode pour récupérer les demandes en attente
    public function getPendingRequests()
    {
        return $this->where('statut', 'en_attente')->findAll();
    }

    public function getEmployeeRequests(int $employeId): array
    {
        return $this->select('conges.*, types_conge.libelle as type_libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeId)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();
    }
}
