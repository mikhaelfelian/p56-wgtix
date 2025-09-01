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
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'price_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'sort_num' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'item_data' => [
                'type'       => 'LONGTEXT',
                'null'       => false,
                'collation'  => 'utf8mb4_bin',
            ],
            'qrcode' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
                'default'    => '',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
                'default'    => '0.00',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
                'default'    => '0.00',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'cancelled'],
                'null'       => false,
                'default'    => 'active',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('event_id');
        $this->forge->addKey('price_id');
        $this->forge->addKey('status');
        $this->forge->addKey('id_penjualan');

        // Add foreign key constraint
        $this->forge->addForeignKey('id_penjualan', 'tbl_trans_jual', 'id', 'CASCADE', 'CASCADE', 'FK_tbl_trans_jual_det_tbl_trans_jual');

        // Create table
        $this->forge->createTable('tbl_trans_jual_det', true, [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);

        // Add CHECK constraint for item_data (json_valid)
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `tbl_trans_jual_det` ADD CONSTRAINT `item_data` CHECK (json_valid(`item_data`))");
    }

    public function down()
    {
        $this->forge->dropTable('tbl_trans_jual_det', true);
    }
}
