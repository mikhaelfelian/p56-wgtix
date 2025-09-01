<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartTable extends Migration
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
            'cart_data' => [
                'type' => 'JSON',
                'null' => false,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'checkout', 'expired'],
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
        $this->forge->addKey('event_id');
        $this->forge->addKey('price_id');
        $this->forge->addKey('status');
        
        $this->forge->createTable('tbl_cart');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_cart');
    }
}
