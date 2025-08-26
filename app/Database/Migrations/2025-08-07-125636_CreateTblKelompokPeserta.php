<?php

/**
 * Migration: Create tbl_kelompok_peserta table
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating kelompok peserta (participant groups) table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblKelompokPeserta20250807125636 extends Migration
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
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID user yang membuat kelompok'
            ],
            'kode_kelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Kode kelompok (KLP001, KLP002, dst)'
            ],
            'nama_kelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nama kelompok peserta'
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Deskripsi kelompok'
            ],
            'kapasitas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
                'comment'    => 'Kapasitas maksimal anggota'
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => 'Status: 0=tidak aktif, 1=aktif'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Indexes
        $this->forge->addKey('id_user');
        $this->forge->addKey('kode_kelompok');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');

        // Create table
        $this->forge->createTable('tbl_kelompok_peserta', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_kelompok_peserta', true);
    }
}
