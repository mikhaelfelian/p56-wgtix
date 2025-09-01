<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblTransJualPlat extends Migration
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
                'null'       => false,
                'comment'    => 'FK ke tbl_trans_jual.id',
            ],
            'id_platform' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'FK ke tbl_m_platform.id',
            ],
            'no_nota' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'comment'    => 'Snapshot nomor nota',
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
                'null'       => false,
                'comment'    => 'Snapshot nama platform',
            ],
            'nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '32,2',
                'null'       => false,
                'default'    => '0.00',
                'comment'    => 'Nominal pembayaran via platform',
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
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

        // Primary & indexes
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_penjualan');
        $this->forge->addKey('id_platform');

        // Foreign keys - commented out temporarily to avoid constraint errors
        // $this->forge->addForeignKey('id_penjualan', 'tbl_trans_jual', 'id', 'CASCADE', 'CASCADE', 'fk_plat_header');
        // $this->forge->addForeignKey('id_platform',  'tbl_m_platform', 'id', 'CASCADE', 'CASCADE', 'fk_plat_platform');

        $this->forge->createTable('tbl_trans_jual_plat', true, [
            'ENGINE'  => 'InnoDB',
            'COMMENT' => 'Pembayaran platform untuk transaksi event',
        ]);
    }

    public function down()
    {
        // No foreign keys to drop since they're commented out
        $this->forge->dropTable('tbl_trans_jual_plat', true);
    }
}
