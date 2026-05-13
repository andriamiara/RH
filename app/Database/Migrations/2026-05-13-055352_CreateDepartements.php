<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartements extends Migration
{
    public function up(): void
{
    $this->forge->addField([
        'id'          => ['type' => 'INTEGER', 'auto_increment' => true],
        'nom'         => ['type' => 'VARCHAR', 'constraint' => 100],
        'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('departements');
}

    public function down()
    {
        //
    }
}
