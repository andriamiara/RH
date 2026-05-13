<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypeConge extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
            'libelle'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'jours_annuels' => ['type' => 'INTEGER'],
            'deductible'    => ['type' => 'INTEGER', 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('types_conge');
    }

    public function down(): void
    {
        $this->forge->dropTable('types_conge', true);
    }
}
