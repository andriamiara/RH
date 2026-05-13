<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeCongeSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('types_conge')->insertBatch([
            ['id' => 1, 'libelle' => 'Conge annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['id' => 2, 'libelle' => 'Conge maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['id' => 3, 'libelle' => 'Conge special', 'jours_annuels' => 5, 'deductible' => 1],
            ['id' => 4, 'libelle' => 'Sans solde', 'jours_annuels' => 0, 'deductible' => 0],
        ]);
    }
}
