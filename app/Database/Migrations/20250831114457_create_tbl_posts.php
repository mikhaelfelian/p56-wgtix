<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblPosts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'id_user'         => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>false,'comment'=>'Penulis/author'],
            'id_category'     => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>true,'comment'=>'Kategori utama (opsional)'],
            'judul'           => ['type'=>'VARCHAR','constraint'=>240,'null'=>false],
            'slug'            => ['type'=>'VARCHAR','constraint'=>260,'null'=>false,'comment'=>'slug-unik-artikel'],
            'excerpt'         => ['type'=>'TEXT','null'=>true,'comment'=>'Ringkasan'],
            'konten'          => ['type'=>'LONGTEXT','null'=>true],
            'cover_image'     => ['type'=>'VARCHAR','constraint'=>255,'null'=>true,'comment'=>'path gambar cover'],
            'status'          => ['type'=>'ENUM','constraint'=>['draft','scheduled','published','archived'],'default'=>'draft'],
            'published_at'    => ['type'=>'DATETIME','null'=>true],
            'views'           => ['type'=>'INT','constraint'=>11,'default'=>0],
            'meta_title'       => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'meta_description' => ['type'=>'VARCHAR','constraint'=>320,'null'=>true],
            'meta_keywords'    => ['type'=>'VARCHAR','constraint'=>320,'null'=>true],
            'created_at'      => ['type'=>'DATETIME','null'=>true],
            'updated_at'      => ['type'=>'DATETIME','null'=>true],
            'deleted_at'      => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['slug'], false, true);
        $this->forge->addKey(['status', 'published_at']);
        $this->forge->addForeignKey('id_category', 'tbl_posts_category', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('tbl_posts', true, ['comment' => 'Table untuk posting/artikel']);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_posts', true);
    }
}
