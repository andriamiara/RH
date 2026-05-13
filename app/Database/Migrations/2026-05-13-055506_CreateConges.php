<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConges extends Migration
{
    public function up(): void
{
    $this->forge->addField([
        'id'            => ['type' => 'INTEGER', 'auto_increment' => true],
        'employe_id'    => ['type' => 'INTEGER'],
        'type_conge_id' => ['type' => 'INTEGER'],
        'date_debut'    => ['type' => 'DATETIME'],
        'date_fin'      => ['type' => 'DATETIME'],
        'nb_jours'      => ['type' => 'INTEGER'],
        'motif'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'statut'        => [
            'type'       => 'VARCHAR', 
            'constraint' => 20, 
            'default'    => 'en_attente'
        ],
        'commentaire_rh'=> ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'created_at'    => ['type' => 'DATETIME', 'null' => true],
        'traite_par'    => ['type' => 'INTEGER', 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('employe_id', 'employes', 'id');
    $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id');
    $this->forge->addForeignKey('traite_par', 'employes', 'id');
    $this->forge->createTable('conges');
}

    public function down()
    {
        //
    }
}
