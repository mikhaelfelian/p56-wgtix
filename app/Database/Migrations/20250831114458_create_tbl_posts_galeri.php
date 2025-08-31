<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblPostsGaleri extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'id_post'      => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'null'=>false],
            'path'         => ['type'=>'VARCHAR','constraint'=>255,'null'=>false,'comment'=>'path file gambar'],
            'caption'      => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'alt_text'     => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'is_primary'   => ['type'=>'TINYINT','constraint'=>1,'default'=>0,'comment'=>'1 gambar utama'],
            'urutan'       => ['type'=>'INT','constraint'=>5,'default'=>0],
            'created_at'   => ['type'=>'DATETIME','null'=>true],
            'updated_at'   => ['type'=>'DATETIME','null'=>true],
            'deleted_at'   => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['id_post', 'urutan']);
        $this->forge->addForeignKey('id_post', 'tbl_posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_posts_galeri', true, ['comment' => 'Table untuk galeri gambar per posting']);
    }

    public function down()
    {
        $this->forge->dropTable('tbl_posts_galeri', true);
    }
}
