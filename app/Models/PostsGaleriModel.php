<?php

namespace App\Models;

use CodeIgniter\Model;

class PostsGaleriModel extends Model
{
    protected $table            = 'tbl_posts_galeri';
    protected $primaryKey       = 'id';

    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'id_post',
        'path',
        'caption',
        'alt_text',
        'is_primary',
        'urutan'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'id_post' => 'required|integer',
        'path'    => 'required|max_length[255]'
    ];
}
