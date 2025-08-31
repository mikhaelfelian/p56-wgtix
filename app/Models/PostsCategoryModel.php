<?php

namespace App\Models;

use CodeIgniter\Model;

class PostsCategoryModel extends Model
{
    protected $table            = 'tbl_posts_category';
    protected $primaryKey       = 'id';

    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'nama', 'slug', 'deskripsi', 'ikon', 'urutan', 'is_active'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'nama' => 'required|min_length[3]|max_length[160]',
        'slug' => 'required|is_unique[tbl_posts_category.slug,id,{id}]',
    ];
}
