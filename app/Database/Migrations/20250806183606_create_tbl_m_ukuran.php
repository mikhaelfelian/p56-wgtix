<?php

/**
 * Migration for tbl_m_ukuran
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-06
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating ukuran (size/racepack) master data table
 * This file represents the Migration for ukuran master data management.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMUkuran extends Migration
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
                'comment'    => 'Kode singkat ukuran (opsional, contoh: S, M, L, XL)',
            ],
            'ukuran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'Nama ukuran (misal: Small, Medium, Large, Extra Large)',
            ],
            'deskripsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'comment'    => 'Deskripsi ukuran (opsional)',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Penjelasan ukuran (opsional)',
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => 0.00,
                'comment'    => 'Harga tambahan untuk ukuran tertentu',
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => 0,
                'comment'    => 'Stok tersedia untuk ukuran ini',
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
        
        $this->forge->createTable('tbl_m_ukuran', true, [
            'ENGINE' => 'InnoDB',
            'COMMENT' => 'Table untuk menyimpan data master ukuran produk/racepack dalam sistem POS'
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_m_ukuran');
    }
}