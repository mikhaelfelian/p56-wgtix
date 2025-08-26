<?php
/**
 * Migration: Create tbl_m_event_harga table
 *
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 25, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating event pricing table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMEventHarga20250807193526 extends Migration
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
            'id_event' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Relasi ke tbl_m_events'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Keterangan harga (opsional)'
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
                'default'    => 0.00,
                'comment'    => 'Nominal harga'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => false,
                'default'    => '1',
                'comment'    => '0=non-aktif, 1=aktif'
            ]
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Indexes
        $this->forge->addKey('id_event');

        // Foreign Key: Relasi ke tbl_m_events
        $this->forge->addForeignKey('id_event', 'tbl_m_event', 'id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('tbl_m_event_harga', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_m_event_harga', true);
    }
}
