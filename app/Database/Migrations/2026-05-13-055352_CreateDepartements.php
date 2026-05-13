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

        $this->db->query(
            'CREATE TEMPORARY TABLE employes_backup AS SELECT * FROM employes'
        );
        $this->forge->dropTable('employes', true);

        $this->forge->addField([
            'id'             => ['type' => 'INTEGER', 'auto_increment' => true],
            'nom'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'          => ['type' => 'VARCHAR', 'constraint' => 150],
            'password'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'           => ['type' => 'VARCHAR', 'constraint' => 20],
            'departement_id' => ['type' => 'INTEGER', 'null' => true],
            'date_embauche'  => ['type' => 'DATETIME', 'null' => true],
            'actif'          => ['type' => 'INTEGER', 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('employes');

        $this->db->query(
            'INSERT INTO employes (id, nom, prenom, email, password, role, departement_id, date_embauche, actif)
             SELECT id, nom, prenom, email, password, role, departement_id, date_embauche, actif FROM employes_backup'
        );
        $this->db->query('DROP TABLE employes_backup');
    }

    public function down(): void
    {
        $this->forge->dropTable('departements', true);
    }
}
