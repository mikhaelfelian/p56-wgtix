<?php

/**
 * Migration: Create tbl_peserta table
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating peserta (participants) table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblPeserta20250807125631 extends Migration
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
                'comment'    => 'ID user yang membuat data peserta'
            ],
            'kode_peserta' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Kode peserta (PES001, PES002, dst)'
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nama lengkap peserta'
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Tempat lahir'
            ],
            'tanggal_lahir' => [
                'type'       => 'DATE',
                'null'       => true,
                'comment'    => 'Tanggal lahir'
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
                'comment'    => 'Jenis kelamin: L=Laki-laki, P=Perempuan'
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Alamat lengkap'
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'comment'    => 'Nomor handphone'
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Email peserta'
            ],
            'id_kelompok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID kelompok peserta'
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID kategori peserta'
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
        $this->forge->addKey('kode_peserta');
        $this->forge->addKey('id_kelompok');
        $this->forge->addKey('id_kategori');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');

        // Create table
        $this->forge->createTable('tbl_peserta', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_peserta', true);
    }
}
