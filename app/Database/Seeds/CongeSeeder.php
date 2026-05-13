<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CongeSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('conges')->insertBatch([
            [
                'employe_id'     => 3,
                'type_conge_id'  => 1,
                'date_debut'     => '2025-06-23 00:00:00',
                'date_fin'       => '2025-06-27 00:00:00',
                'nb_jours'       => 5,
                'motif'          => 'Repos annuel',
                'statut'         => 'en_attente',
                'commentaire_rh' => null,
                'created_at'     => '2025-06-10 09:00:00',
                'traite_par'     => null,
            ],
            [
                'employe_id'     => 3,
                'type_conge_id'  => 2,
                'date_debut'     => '2025-06-02 00:00:00',
                'date_fin'       => '2025-06-03 00:00:00',
                'nb_jours'       => 2,
                'motif'          => 'Repos medical',
                'statut'         => 'approuvee',
                'commentaire_rh' => 'Valide',
                'created_at'     => '2025-05-28 11:00:00',
                'traite_par'     => 2,
            ],
            [
                'employe_id'     => 4,
                'type_conge_id'  => 2,
                'date_debut'     => '2025-06-18 00:00:00',
                'date_fin'       => '2025-06-19 00:00:00',
                'nb_jours'       => 2,
                'motif'          => 'Consultation',
                'statut'         => 'en_attente',
                'commentaire_rh' => null,
                'created_at'     => '2025-06-15 08:30:00',
                'traite_par'     => null,
            ],
        ]);
    }
}
