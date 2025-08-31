<?php

namespace App\Models;

use CodeIgniter\Model;

class PostsModel extends Model
{
    protected $table            = 'tbl_posts';
    protected $primaryKey       = 'id';

    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'id_user',
        'id_category',
        'judul',
        'slug',
        'excerpt',
        'konten',
        'cover_image',
        'status',
        'published_at',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'judul' => 'required|min_length[3]|max_length[240]',
        'slug'  => 'required|is_unique[tbl_posts.slug,id,{id}]',
        'status'=> 'in_list[draft,scheduled,published,archived]'
    ];
}
