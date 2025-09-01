<?php

/**
 * Create tbl_trans_jual_plat Table Migration
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: Create table for storing transaction payment platform details
 * This file represents the CreateTransJualPlatTable Migration.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransJualPlatTable extends Migration
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
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_platform' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'no_nota' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
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
        $this->forge->addKey('id_penjualan');
        $this->forge->addKey('id_platform');
        
        // Add foreign key constraint
        $this->forge->addForeignKey('id_penjualan', 'tbl_trans_jual', 'id', 'CASCADE', 'CASCADE', 'FK_tbl_trans_jual_plat_penjualan');
        
        $this->forge->createTable('tbl_trans_jual_plat');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_trans_jual_plat');
    }
}