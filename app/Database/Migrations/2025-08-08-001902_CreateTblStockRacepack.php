<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblStockRacepack20250808001902 extends Migration
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
            'id_racepack' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_ukuran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stok_masuk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'stok_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'stok_tersedia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'minimal_stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_racepack', 'tbl_racepack', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_ukuran', 'tbl_m_ukuran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_stock_racepack');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_stock_racepack');
    }
}
