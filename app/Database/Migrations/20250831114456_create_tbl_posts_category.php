<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblPostsCategory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'nama'         => ['type' => 'VARCHAR','constraint'=>160,'null'=>false,'comment'=>'Nama kategori'],
            'slug'         => ['type' => 'VARCHAR','constraint'=>180,'null'=>false,'comment'=>'slug-unik-kategori'],
            'deskripsi'    => ['type' => 'TEXT','null'=>true],
            'ikon'         => ['type' => 'VARCHAR','constraint'=>120,'null'=>true,'comment'=>'class/icon path opsional'],
            'urutan'       => ['type' => 'INT','constraint'=>5,'default'=>0],
            'is_active'    => ['type' => 'TINYINT','constraint'=>1,'default'=>1,'comment'=>'1 aktif, 0 nonaktif'],
            'created_at'   => ['type' => 'DATETIME','null'=>true],
            'updated_at'   => ['type' => 'DATETIME','null'=>true],
            'deleted_at'   => ['type' => 'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['slug'], false, true);
        $this->forge->createTable('tbl_posts_category', true, ['comment' => 'Table untuk master kategori posting']);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_posts_category', true);
    }
}
