<?php
/**
 * Migration: Create tbl_m_event_galeri table
 *
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: August 25, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating event gallery table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMEventGaleri20250807194526 extends Migration
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
            'file' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
                'comment'    => 'Nama file atau URL media'
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Deskripsi media'
            ],
            'is_cover' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'comment'    => '1=cover utama event, 0=bukan cover'
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '0=non-aktif, 1=aktif'
            ]
        ]);

        // Primary key
        $this->forge->addKey('id', true);

        // Indexes
        $this->forge->addKey('id_event');
        $this->forge->addKey('is_cover');

        // Foreign Key
        $this->forge->addForeignKey('id_event', 'tbl_m_event', 'id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('tbl_m_event_galeri', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_m_event_galeri', true);
    }
}
