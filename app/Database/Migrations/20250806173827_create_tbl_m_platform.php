<?php

/**
 * Migration for tbl_m_platform
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating platform master data table
 * This file represents the Migration for platform master data management.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMPlatform extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
                'null'       => false,
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
                'null'       => false,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'nama_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => 111,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'nomor_rekening' => [
                'type'       => 'VARCHAR',
                'constraint' => 111,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'deskripsi' => [
                'type'    => 'TEXT',
                'null'    => false,
                'collate' => 'utf8_swedish_ci',
            ],
            'gateway_kode' => [
                'type'       => 'VARCHAR',
                'constraint' => 111,
                'null'       => true,
                'default'    => '-',
                'collate'    => 'utf8_swedish_ci',
            ],
            'gateway_instruksi' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => true,
                'default'    => '0',
                'collate'    => 'utf8_swedish_ci',
            ],
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 111,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'hasil' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'collate'    => 'utf8_swedish_ci',
            ],
            'status' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => false,
                'null'       => false,
            ],
            'status_gateway' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => false,
                'default'    => '0',
                'collate'    => 'utf8_swedish_ci',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tbl_m_platform', true);
        
        // Add table comment
        $this->db->query("ALTER TABLE `tbl_m_platform` COMMENT = 'Table untuk menyimpan data master platform pembayaran dan gateway'");
    }

    public function down()
    {
        $this->forge->dropTable('tbl_m_platform');
    }
}