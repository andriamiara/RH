<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->call(DepartementSeeder::class);
        $this->call(TypeCongeSeeder::class);
        $this->call(EmployeSeeder::class);
        $this->call(SoldeSeeder::class);
        $this->call(CongeSeeder::class);
    }
}
