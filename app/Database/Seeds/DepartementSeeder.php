<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('departements')->insertBatch([
            ['id' => 1, 'nom' => 'IT', 'description' => 'Equipe technique et support.'],
            ['id' => 2, 'nom' => 'RH', 'description' => 'Gestion administrative et ressources humaines.'],
            ['id' => 3, 'nom' => 'Finance', 'description' => 'Comptabilite et controle de gestion.'],
            ['id' => 4, 'nom' => 'Marketing', 'description' => 'Communication et croissance.'],
        ]);
    }
}
