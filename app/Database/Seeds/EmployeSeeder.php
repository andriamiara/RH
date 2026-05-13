<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('employes')->insertBatch([
            [
                'id'             => 1,
                'nom'            => 'Admin',
                'prenom'         => 'Super',
                'email'          => 'admin@techmada.mg',
                'password'       => password_hash('admin123', PASSWORD_DEFAULT),
                'role'           => 'admin',
                'departement_id' => 2,
                'date_embauche'  => '2020-01-10 00:00:00',
                'actif'          => 1,
            ],
            [
                'id'             => 2,
                'nom'            => 'Rabe',
                'prenom'         => 'Marie',
                'email'          => 'rh@techmada.mg',
                'password'       => password_hash('rh123', PASSWORD_DEFAULT),
                'role'           => 'rh',
                'departement_id' => 2,
                'date_embauche'  => '2020-01-15 00:00:00',
                'actif'          => 1,
            ],
            [
                'id'             => 3,
                'nom'            => 'Rakoto',
                'prenom'         => 'Soa',
                'email'          => 'employe@techmada.mg',
                'password'       => password_hash('emp123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => 1,
                'date_embauche'  => '2022-03-01 00:00:00',
                'actif'          => 1,
            ],
            [
                'id'             => 4,
                'nom'            => 'Fidy',
                'prenom'         => 'Tsiry',
                'email'          => 'tsiry@techmada.mg',
                'password'       => password_hash('tsiry123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => 3,
                'date_embauche'  => '2019-07-10 00:00:00',
                'actif'          => 0,
            ],
        ]);
    }
}
