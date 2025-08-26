<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblRacepack20250807234755 extends Migration
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
            'kode_racepack' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'Kode racepack (RCP001, RCP002, dst)'
            ],
            'nama_racepack' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
                'comment'    => 'Nama racepack'
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID kategori racepack'
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Deskripsi racepack'
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'default'    => 0.00,
                'comment'    => 'Harga racepack'
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Path gambar racepack'
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
        $this->forge->addKey('kode_racepack');
        $this->forge->addKey('id_kategori');
        $this->forge->addKey('status');
        $this->forge->addKey('id_user');
        $this->forge->createTable('tbl_racepack');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_racepack');
    }
}
