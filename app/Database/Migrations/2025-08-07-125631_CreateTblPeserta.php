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
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_platform' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_kelompok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'ID kelompok peserta'
            ],
            'id_event' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Kode peserta (PES001, PES002, dst)',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nama lengkap peserta',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'tmp_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Tempat lahir',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'tgl_lahir' => [
                'type'       => 'DATE',
                'null'       => true,
                'default'    => null,
                'comment'    => 'Tanggal lahir'
            ],
            'jns_klm' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
                'comment'    => 'Jenis kelamin: L=Laki-laki, P=Perempuan',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => true,
                'default'    => null,
                'comment'    => 'Alamat lengkap',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Nomor handphone',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Email peserta',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'qr_code' => [
                'type'       => 'TEXT',
                'null'       => true,
                'default'    => null,
                'comment'    => 'QR Code as base64 string (max 200KB)',
                'collation'  => 'utf8mb4_general_ci',
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => 'Status: 0=tidak aktif, 1=aktif'
            ],
            'status_hadir' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => false,
                'default'    => '0',
                'comment'    => 'Status hadir: 0=tidak hadir, 1=hadir',
                'collation'  => 'utf8mb4_general_ci',
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Indexes
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_kelompok');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');
        $this->forge->addKey('kode'); // kode_peserta renamed to kode

        // Create table
        $this->forge->createTable('tbl_peserta', true, [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_peserta', true);
    }
}
