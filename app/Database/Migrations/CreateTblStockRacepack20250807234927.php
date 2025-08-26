<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblStockRacepack20250807234927 extends Migration
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
                'null'       => false,
                'comment'    => 'ID racepack'
            ],
            'id_ukuran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID ukuran'
            ],
            'stok_masuk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Jumlah stok masuk'
            ],
            'stok_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Jumlah stok keluar'
            ],
            'stok_tersedia' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Stok tersedia (masuk - keluar)'
            ],
            'minimal_stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Minimal stok untuk warning'
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1: Aktif, 0: Tidak Aktif'
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
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
        $this->forge->addKey('id_racepack');
        $this->forge->addKey('id_ukuran');
        $this->forge->addKey('status');
        $this->forge->addKey('id_user');
        $this->forge->createTable('tbl_stock_racepack');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_stock_racepack');
    }
}
