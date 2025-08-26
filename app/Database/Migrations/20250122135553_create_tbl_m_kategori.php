<?php

/**
 * Migration for tbl_m_kategori
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating kategori (categories) master data table
 * This file represents the Migration for kategori master data management.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMKategori extends Migration
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
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'Kode singkat kategori (opsional, contoh: 5KUM, 10KP)',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nama kategori (misal: 5K Umum, 10K Pelajar)',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Penjelasan kategori (opsional)',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'default'    => '1',
                'null'       => false,
                'comment'    => 'Status aktif/tidak aktif untuk toggle saat pendaftaran',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_user');
        
        // Add indexes for better performance
        $this->forge->addKey('kode');
        $this->forge->addKey('status');
        
        $this->forge->createTable('tbl_m_kategori', true, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Table untuk menyimpan data master kategori produk/layanan dalam sistem POS'
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_m_kategori');
    }
}