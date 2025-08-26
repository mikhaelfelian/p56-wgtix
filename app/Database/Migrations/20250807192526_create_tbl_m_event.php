<?php
/**
 * Migration: Create tbl_m_events table
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: January 22, 2025
 * Github: github.com/mikhaelfelian
 * Description: Migration for creating events (jadwal) master data table
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblMEvent20250807192526 extends Migration
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
                'comment'    => 'ID user yang membuat event'
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID kategori event'
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
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'comment'    => 'Kode event (opsional)'
            ],
            'event' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'comment'    => 'Nama event'
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
                'null'       => true,
                'comment'    => 'Foto event'
            ],
            'tgl_masuk' => [
                'type'       => 'DATE',
                'null'       => false,
                'comment'    => 'Tanggal mulai event'
            ],
            'tgl_keluar' => [
                'type'       => 'DATE',
                'null'       => false,
                'comment'    => 'Tanggal selesai event'
            ],
            'wkt_masuk' => [
                'type'       => 'TIME',
                'null'       => false,
                'comment'    => 'Waktu mulai event'
            ],
            'wkt_keluar' => [
                'type'       => 'TIME',
                'null'       => false,
                'comment'    => 'Waktu selesai event'
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
                'comment'    => 'Lokasi event'
            ],
            'jml' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
                'comment'    => 'Kapasitas peserta'
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'comment'    => 'Latitude lokasi event'
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'comment'    => 'Longitude lokasi event'
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Keterangan event'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => false,
                'default'    => '1',
                'comment'    => 'Status: 0=tidak aktif, 1=aktif'
            ],
            'status_hps' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'null'       => false,
                'default'    => '0',
                'comment'    => 'Status Hapus: 0=aktif, 1=terhapus'
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);

        // Indexes
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_kategori');
        $this->forge->addKey('kode');
        $this->forge->addKey('status');
        $this->forge->addKey('tgl_masuk');
        $this->forge->addKey('tgl_keluar');
        $this->forge->addKey('wkt_masuk');
        $this->forge->addKey('wkt_keluar');
        $this->forge->addKey('created_at');

        // Foreign Key: tbl_m_kategori.id = tbl_m_events.id_kategori
        $this->forge->addForeignKey('id_kategori', 'tbl_m_kategori', 'id', 'CASCADE', 'CASCADE');

        // Create table
        $this->forge->createTable('tbl_m_event', true);
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('tbl_m_event', true);
    }
} 