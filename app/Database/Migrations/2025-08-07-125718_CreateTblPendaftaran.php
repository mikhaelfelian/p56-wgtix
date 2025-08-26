<?php

/**
 * Migration: Create tbl_pendaftaran table
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating pendaftaran (registration) table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblPendaftaran20250807125718 extends Migration
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
                'comment'    => 'ID user yang membuat pendaftaran'
            ],
            'kode_pendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'comment'    => 'Kode pendaftaran (REG001, REG002, dst)'
            ],
            'id_peserta' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID peserta yang mendaftar'
            ],
            'id_jadwal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID jadwal yang dipilih'
            ],
            'tanggal_pendaftaran' => [
                'type'       => 'DATE',
                'null'       => false,
                'comment'    => 'Tanggal pendaftaran'
            ],
            'status_pendaftaran' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'cancelled'],
                'null'       => false,
                'default'    => 'pending',
                'comment'    => 'Status pendaftaran'
            ],
            'catatan' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Catatan pendaftaran'
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
        $this->forge->addKey('kode_pendaftaran');
        $this->forge->addKey('id_peserta');
        $this->forge->addKey('id_jadwal');
        $this->forge->addKey('status_pendaftaran');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');

        // Create table
        $this->forge->createTable('tbl_pendaftaran', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_pendaftaran', true);
    }
}
