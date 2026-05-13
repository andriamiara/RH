<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployes extends Migration
{
    public function up(): void
{
    $this->forge->addField([
        'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
        'nom'            => ['type' => 'VARCHAR', 'constraint' => 100],
        'prenom'         => ['type' => 'VARCHAR', 'constraint' => 100],
        'email'          => ['type' => 'VARCHAR', 'constraint' => 150],
        'password'       => ['type' => 'VARCHAR', 'constraint' => 255],
        'role'           => ['type' => 'VARCHAR', 'constraint' => 20],
        'departement_id' => ['type' => 'INTEGER'],
        'date_embauche'  => ['type' => 'DATETIME', 'null' => true],
        'actif'          => ['type' => 'INTEGER', 'default' => 1], // 0 ou 1 pour SQLite
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('departement_id', 'departements', 'id', 'CASCADE', 'SET NULL');
    $this->forge->createTable('employes');
}

    public function down()
    {
        //
    }
}
