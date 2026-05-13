<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoldeSeeder extends Seeder
{
    public function run()
    {
        $annee = 2025;

        $this->db->table('soldes')->insertBatch([
            ['employe_id' => 1, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 2],
            ['employe_id' => 1, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 10, 'jours_pris' => 0],
            ['employe_id' => 1, 'type_conge_id' => 3, 'annee' => $annee, 'jours_attribues' => 5, 'jours_pris' => 0],
            ['employe_id' => 1, 'type_conge_id' => 4, 'annee' => $annee, 'jours_attribues' => 0, 'jours_pris' => 0],
            ['employe_id' => 2, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 5],
            ['employe_id' => 2, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 10, 'jours_pris' => 1],
            ['employe_id' => 2, 'type_conge_id' => 3, 'annee' => $annee, 'jours_attribues' => 5, 'jours_pris' => 0],
            ['employe_id' => 2, 'type_conge_id' => 4, 'annee' => $annee, 'jours_attribues' => 0, 'jours_pris' => 0],
            ['employe_id' => 3, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 12],
            ['employe_id' => 3, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 10, 'jours_pris' => 2],
            ['employe_id' => 3, 'type_conge_id' => 3, 'annee' => $annee, 'jours_attribues' => 5, 'jours_pris' => 4],
            ['employe_id' => 3, 'type_conge_id' => 4, 'annee' => $annee, 'jours_attribues' => 0, 'jours_pris' => 0],
        ]);
    }
}
