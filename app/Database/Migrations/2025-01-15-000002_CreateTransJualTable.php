<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransJualTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
            ],
            'invoice_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed', 'cancelled'],
                'default'    => 'pending',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'cancelled', 'completed'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('session_id');
        $this->forge->addKey('invoice_date');
        $this->forge->addKey('payment_status');
        $this->forge->addKey('status');
        
        $this->forge->createTable('tbl_trans_jual');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_trans_jual');
    }
}
