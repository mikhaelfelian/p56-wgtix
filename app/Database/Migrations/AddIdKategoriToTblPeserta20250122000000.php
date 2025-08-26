<?php

/**
 * Migration: Add id_kategori to tbl_peserta table
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for adding id_kategori field to peserta table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdKategoriToTblPeserta20250122000000 extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_peserta', [
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_kelompok',
                'comment'    => 'ID kategori peserta'
            ]
        ]);

        // Add index for id_kategori
        $this->forge->addKey('id_kategori');
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_peserta', 'id_kategori');
    }
} 