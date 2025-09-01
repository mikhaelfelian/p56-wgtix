<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransJualDetTable extends Migration
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
            'invoice_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'price_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_data' => [
                'type' => 'JSON',
                'null' => false,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'cancelled'],
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
        $this->forge->addKey('invoice_id');
        $this->forge->addKey('event_id');
        $this->forge->addKey('price_id');
        $this->forge->addKey('status');
        
        // Add foreign key constraint
        $this->forge->addForeignKey('invoice_id', 'tbl_trans_jual', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('tbl_trans_jual_det');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_trans_jual_det');
    }
}
