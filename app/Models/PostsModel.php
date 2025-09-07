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

    protected $validationRules  = [];

    /**
     * Get posts with filters, pagination, and search
     */
    public function getPostsWithFilters($perPage, $keyword = '', $page = 1, $kategori = '', $status = null, $activeOnly = true)
    {
        $builder = $this->builder();
        
        // Apply filters
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('excerpt', $keyword)
                    ->orLike('konten', $keyword)
                    ->groupEnd();
        }
        
        if (!empty($kategori)) {
            $builder->where('id_category', $kategori);
        }
        
        if ($status !== null) {
            $builder->where('status', $status);
        }
        
        if ($activeOnly) {
            $builder->where('status', 'published');
        }
        
        // Order by created date
        $builder->orderBy('created_at', 'DESC');
        
        // Apply pagination
        $pager = $this->paginate($perPage, 'default', $page);
        
        // Return the posts data directly
        return $pager;
    }

    /**
     * Get total posts count with filters
     */
    public function getTotalPosts($keyword = '', $kategori = '', $status = null, $activeOnly = false)
    {
        $builder = $this->builder();
        
        // Apply filters
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('excerpt', $keyword)
                    ->orLike('konten', $keyword)
                    ->groupEnd();
        }
        
        if (!empty($kategori)) {
            $builder->where('id_category', $kategori);
        }
        
        if ($status !== null) {
            $builder->where('status', $status);
        }
        
        if ($activeOnly) {
            $builder->where('status', 'published');
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get posts by category
     */
    public function getPostsByCategory($categoryId, $perPage = 10, $page = 1, $keyword = '', $status = null)
    {
        $builder = $this->builder();
        
        $builder->where('id_category', $categoryId);
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('excerpt', $keyword)
                    ->orLike('konten', $keyword)
                    ->groupEnd();
        }
        
        if ($status !== null) {
            $builder->where('status', $status);
        }
        
        $builder->orderBy('created_at', 'DESC');
        
        return $this->paginate($perPage, 'default', $page);
    }

    /**
     * Get published posts for frontend
     */
    public function getPublishedPosts($perPage = 10, $page = 1, $keyword = '', $categoryId = null)
    {
        $builder = $this->builder();
        
        $builder->where('status', 'published');
        
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('judul', $keyword)
                    ->orLike('excerpt', $keyword)
                    ->orLike('konten', $keyword)
                    ->groupEnd();
        }
        
        if ($categoryId !== null) {
            $builder->where('id_category', $categoryId);
        }
        
        $builder->orderBy('published_at', 'DESC');
        
        return $this->paginate($perPage, 'default', $page);
    }
}
