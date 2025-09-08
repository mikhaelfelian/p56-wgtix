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
        'nama',
        'slug',
        'deskripsi',
        'ikon',
        'urutan',
        'is_active'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [];

    /**
     * Get categories with filters, search, and pagination
     *
     * @param int $perPage Number of items per page
     * @param string $keyword Search keyword
     * @param int $page Current page number
     * @param string|null $status Filter by status ('active', 'inactive', or null)
     * @return array Array containing categories and total count
     */
    public function getCategoriesWithFilters($perPage = 10, $keyword = '', $page = 1, $status = null)
    {
        $builder = $this->builder();

        // Apply keyword search
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('nama', $keyword)
                ->orLike('slug', $keyword)
                ->orLike('deskripsi', $keyword)
                ->groupEnd();
        }

        // Apply status filter
        if ($status !== null) {
            if ($status === 'active') {
                $builder->where('is_active', '1');
            } elseif ($status === 'inactive') {
                $builder->where('is_active', '0');
            }
        }

        // Order by urutan (order) and then by nama
        $builder->orderBy('urutan', 'ASC')
                ->orderBy('nama', 'ASC');

        // Get total count for pagination
        $total = $builder->countAllResults(false);

        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $categories = $builder->limit($perPage, $offset)->get()->getResult();

        return [
            'categories' => $categories,
            'total' => $total
        ];
    }

    /**
     * Get total count of categories with filters
     *
     * @param string $keyword Search keyword
     * @param string|null $status Filter by status
     * @return int Total count
     */
    public function getTotalCategories($keyword = '', $status = null)
    {
        $builder = $this->builder();

        // Apply keyword search
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('nama', $keyword)
                ->orLike('slug', $keyword)
                ->orLike('deskripsi', $keyword)
                ->groupEnd();
        }

        // Apply status filter
        if ($status !== null) {
            if ($status === 'active') {
                $builder->where('is_active', '1');
            } elseif ($status === 'inactive') {
                $builder->where('is_active', '0');
            }
        }

        return $builder->countAllResults();
    }

    /**
     * Get all active categories ordered by urutan and nama
     *
     * @return array
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('nama', 'ASC')
                    ->findAll();
    }

    /**
     * Get categories by status
     *
     * @param bool $isActive
     * @param int|null $limit
     * @return array
     */
    public function getCategoriesByStatus($isActive = true, $limit = null)
    {
        $builder = $this->where('is_active', $isActive ? 1 : 0)
                        ->orderBy('urutan', 'ASC')
                        ->orderBy('nama', 'ASC');

        if ($limit !== null) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Update category order (urutan)
     *
     * @param int $id
     * @param int $order
     * @return bool
     */
    public function updateOrder($id, $order)
    {
        return $this->update($id, ['urutan' => $order]);
    }

    /**
     * Toggle category status (is_active)
     *
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id)
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }
        $newStatus = $category->is_active ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Check if category has associated posts
     *
     * @param int $id
     * @return bool
     */
    public function hasPosts($id)
    {
        // Implement this method if you have a PostsModel and relationship
        // For now, return false as a placeholder
        return false;
    }
}
