<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostsCategoryModel;

/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-31
 * Github: github.com/mikhaelfelian
 * 
 * This file represents the BeritaCategory Controller for managing news categories in the admin panel.
 */
class BeritaCategory extends BaseController
{
    protected $categoryModel;
    protected $pengaturan;
    protected $ionAuth;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new PostsCategoryModel();
        $this->pengaturan = $this->getPengaturan();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $keyword = $this->request->getGet('keyword') ?? '';
        $perPage = $this->pengaturan->pagination_limit ?? 10;

        // Get categories with pagination and search
        $result = $this->categoryModel->getCategoriesWithFilters($perPage, $keyword, $page);
        $categories = $result['categories'];
        $total = $result['total'];

        // Create pager
        $pager = service('pager');
        $pager->setPath('admin/berita-category');
        $pager->makeLinks($total, $perPage, $page, 'adminlte_pagination');

        $data = [
            'title'        => 'Kelola Kategori Berita',
            'categories'   => $categories,
            'pager'        => $pager,
            'currentPage'  => $page,
            'perPage'      => $perPage,
            'keyword'      => $keyword,
            'total'        => $total,
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita-category/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori Baru',
            'Pengaturan' => $this->pengaturan,
            'user' => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita-category/create', $data);
    }

    public function store()
    {
        // Get category ID for edit mode
        $categoryId = $this->request->getPost('id');
        $isEdit = !empty($categoryId);

        // Validation rules - adjust for edit mode
        $rules = [
            'nama' => 'required|min_length[3]|max_length[160]',
            'slug' => $isEdit ? 'required|is_unique[tbl_posts_category.slug,id,' . $categoryId . ']' : 'required|is_unique[tbl_posts_category.slug]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'ikon' => 'permit_empty|max_length[100]',
            'urutan' => 'permit_empty|integer',
            'is_active' => 'required|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare category data
        $categoryData = [
            'nama' => $this->request->getPost('nama'),
            'slug' => $this->request->getPost('slug'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'ikon' => $this->request->getPost('ikon'),
            'urutan' => $this->request->getPost('urutan') ?? 0,
            'is_active' => $this->request->getPost('is_active')
        ];

        // Add ID for edit mode
        if ($isEdit) {
            $categoryData['id'] = $categoryId;
        }

        // Save category using save() method
        if ($this->categoryModel->save($categoryData)) {
            $message = $isEdit ? 'Kategori berhasil diupdate' : 'Kategori berhasil ditambahkan';
            return redirect()->to('admin/berita-category')->with('success', $message);
        }

        $errorMessage = $isEdit ? 'Gagal mengupdate kategori' : 'Gagal menambahkan kategori';
        return redirect()->back()->withInput()->with('error', $errorMessage);
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/berita-category')->with('error', 'Kategori tidak ditemukan');
        }

        $data = [
            'title'      => 'Edit Kategori',
            'category'   => $category,
            'Pengaturan' => $this->pengaturan,
            'user'       => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita-category/create', $data);
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/berita-category')->with('error', 'Kategori tidak ditemukan');
        }

        // Check if category has posts
        $postsModel = new \App\Models\PostsModel();
        $postCount = $postsModel->where('id_category', $id)->countAllResults();
        
        if ($postCount > 0) {
            return redirect()->to('admin/berita-category')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki berita');
        }

        // Delete category (soft delete)
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('admin/berita-category')->with('success', 'Kategori berhasil dihapus');
        }

        return redirect()->to('admin/berita-category')->with('error', 'Gagal menghapus kategori');
    }

    public function toggleStatus($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kategori tidak ditemukan']);
        }

        $newStatus = $category->is_active ? 0 : 1;
        
        if ($this->categoryModel->update($id, ['is_active' => $newStatus])) {
            $statusText = $newStatus ? 'aktifkan' : 'nonaktifkan';
            return $this->response->setJSON([
                'success' => true, 
                'message' => "Kategori berhasil di{$statusText}",
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status kategori']);
    }

    public function updateOrder()
    {
        $orders = $this->request->getPost('orders');
        
        if ($orders) {
            foreach ($orders as $id => $order) {
                $this->categoryModel->update($id, ['urutan' => $order]);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Urutan kategori berhasil diupdate']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Data urutan tidak valid']);
    }

    /**
     * Get pengaturan data for views
     */
    private function getPengaturan()
    {
        $pengaturanModel = new \App\Models\PengaturanModel();
        return $pengaturanModel->first();
    }
}
