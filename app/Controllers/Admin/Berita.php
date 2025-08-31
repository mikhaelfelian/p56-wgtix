<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostsModel;
use App\Models\PostsCategoryModel;
use App\Models\PostsGaleriModel;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-31
 * Github: github.com/mikhaelfelian
 * 
 * This file represents the Berita Controller for managing news posts in the admin panel.
 */
class Berita extends BaseController
{
    protected $postsModel;
    protected $categoryModel;
    protected $galeriModel;

    public function __construct()
    {
        $this->postsModel = new PostsModel();
        $this->categoryModel = new PostsCategoryModel();
        $this->galeriModel = new PostsGaleriModel();
    }

    public function index()
    {
        $perPage = 10;
        $page = $this->request->getGet('page') ?? 1;
        $keyword = $this->request->getGet('keyword') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';
        
        // Get posts with pagination
        $posts = $this->postsModel->getPostsWithFilters($perPage, $keyword, $page, $kategori);
        
        // Get pagination
        $pager = $this->postsModel->pager;
        
        // Get total count
        $total = $this->postsModel->getTotalPosts($keyword, $kategori);
        
        // Get categories for filter
        $categories = $this->categoryModel->where('is_active', 1)->findAll();
        
        $data = [
            'title' => 'Data Berita',
            'posts' => $posts,
            'categories' => $categories,
            'pager' => $pager,
            'currentPage' => $page,
            'perPage' => $perPage,
            'keyword' => $keyword,
            'total' => $total,
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Berita Baru',
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita/create', $data);
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'judul' => 'required|min_length[3]|max_length[240]',
            'slug' => 'required|is_unique[tbl_posts.slug,id,{id}]',
            'konten' => 'required',
            'id_category' => 'required|integer',
            'status' => 'required|in_list[draft,scheduled,published,archived]',
            'cover_image' => 'uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle cover image upload
        $coverImage = $this->request->getFile('cover_image');
        $coverImageName = null;

        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = $coverImage->getRandomName();
            $coverImage->move(ROOTPATH . 'public/uploads/berita/cover', $coverImageName);
        }

        // Prepare post data
        $postData = [
            'id_user' => session()->get('user_id') ?? 1, // Default user ID
            'id_category' => $this->request->getPost('id_category'),
            'judul' => $this->request->getPost('judul'),
            'slug' => $this->request->getPost('slug'),
            'excerpt' => $this->request->getPost('excerpt'),
            'konten' => $this->request->getPost('konten'),
            'cover_image' => $coverImageName,
            'status' => $this->request->getPost('status'),
            'published_at' => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'meta_keywords' => $this->request->getPost('meta_keywords')
        ];

        // Save post
        $postId = $this->postsModel->insert($postData);

        if ($postId) {
            // Handle gallery images
            $galleryImages = $this->request->getFileMultiple('gallery_images');
            
            if ($galleryImages) {
                foreach ($galleryImages as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $imageName = $image->getRandomName();
                        $image->move(ROOTPATH . 'public/uploads/berita/gallery', $imageName);

                        $galeriData = [
                            'id_post' => $postId,
                            'path' => $imageName,
                            'caption' => $this->request->getPost('image_caption_' . $image->getClientName()),
                            'alt_text' => $this->request->getPost('image_alt_' . $image->getClientName()),
                            'is_primary' => 0,
                            'urutan' => 0
                        ];

                        $this->galeriModel->insert($galeriData);
                    }
                }
            }

            return redirect()->to('admin/berita')->with('success', 'Berita berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan berita');
    }

    public function edit($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Berita',
            'post' => $post,
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'gallery' => $this->galeriModel->where('id_post', $id)->findAll(),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita/edit', $data);
    }

    public function update($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'judul' => 'required|min_length[3]|max_length[240]',
            'slug' => "required|is_unique[tbl_posts.slug,id,{$id}]",
            'konten' => 'required',
            'id_category' => 'required|integer',
            'status' => 'required|in_list[draft,scheduled,published,archived]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle cover image upload
        $coverImage = $this->request->getFile('cover_image');
        $coverImageName = $post->cover_image; // Keep existing if no new upload

        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            // Delete old cover image
            if ($post->cover_image && file_exists(ROOTPATH . 'public/uploads/berita/cover/' . $post->cover_image)) {
                unlink(ROOTPATH . 'public/uploads/berita/cover/' . $post->cover_image);
            }

            $coverImageName = $coverImage->getRandomName();
            $coverImage->move(ROOTPATH . 'public/uploads/berita/cover', $coverImageName);
        }

        // Prepare post data
        $postData = [
            'id_category' => $this->request->getPost('id_category'),
            'judul' => $this->request->getPost('judul'),
            'slug' => $this->request->getPost('slug'),
            'excerpt' => $this->request->getPost('excerpt'),
            'konten' => $this->request->getPost('konten'),
            'cover_image' => $coverImageName,
            'status' => $this->request->getPost('status'),
            'published_at' => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'meta_keywords' => $this->request->getPost('meta_keywords')
        ];

        // Update post
        if ($this->postsModel->update($id, $postData)) {
            return redirect()->to('admin/berita')->with('success', 'Berita berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate berita');
    }

    public function delete($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Delete cover image
        if ($post->cover_image && file_exists(ROOTPATH . 'public/uploads/berita/cover/' . $post->cover_image)) {
            unlink(ROOTPATH . 'public/uploads/berita/cover/' . $post->cover_image);
        }

        // Delete gallery images
        $gallery = $this->galeriModel->where('id_post', $id)->findAll();
        foreach ($gallery as $image) {
            if (file_exists(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path)) {
                unlink(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path);
            }
        }

        // Delete post (soft delete)
        if ($this->postsModel->delete($id)) {
            return redirect()->to('admin/berita')->with('success', 'Berita berhasil dihapus');
        }

        return redirect()->to('admin/berita')->with('error', 'Gagal menghapus berita');
    }

    public function gallery($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        $data = [
            'title' => 'Galeri Berita: ' . $post->judul,
            'post' => $post,
            'gallery' => $this->galeriModel->where('id_post', $id)->orderBy('urutan', 'ASC')->findAll(),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita/gallery', $data);
    }

    public function uploadGallery($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return $this->response->setJSON(['success' => false, 'message' => 'Berita tidak ditemukan']);
        }

        $images = $this->request->getFileMultiple('images');
        $uploaded = 0;

        if ($images) {
            foreach ($images as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/uploads/berita/gallery', $imageName);

                    $galeriData = [
                        'id_post' => $id,
                        'path' => $imageName,
                        'caption' => $this->request->getPost('caption'),
                        'alt_text' => $this->request->getPost('alt_text'),
                        'is_primary' => 0,
                        'urutan' => $this->galeriModel->where('id_post', $id)->countAllResults() + 1
                    ];

                    if ($this->galeriModel->insert($galeriData)) {
                        $uploaded++;
                    }
                }
            }
        }

        return $this->response->setJSON([
            'success' => true, 
            'message' => "Berhasil upload {$uploaded} gambar",
            'uploaded' => $uploaded
        ]);
    }

    public function deleteGalleryImage($id)
    {
        $image = $this->galeriModel->find($id);
        
        if (!$image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
        }

        // Delete file
        if (file_exists(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path)) {
            unlink(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path);
        }

        // Delete record
        if ($this->galeriModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Gambar berhasil dihapus']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus gambar']);
    }

    public function setPrimaryImage($id)
    {
        $image = $this->galeriModel->find($id);
        
        if (!$image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
        }

        // Reset all primary images for this post
        $this->galeriModel->where('id_post', $image->id_post)->set(['is_primary' => 0])->update();

        // Set this image as primary
        if ($this->galeriModel->update($id, ['is_primary' => 1])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Gambar utama berhasil diatur']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengatur gambar utama']);
    }

    public function updateImageOrder()
    {
        $orders = $this->request->getPost('orders');
        
        if ($orders) {
            foreach ($orders as $id => $order) {
                $this->galeriModel->update($id, ['urutan' => $order]);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Urutan gambar berhasil diupdate']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Data urutan tidak valid']);
    }

    /**
     * Search posts with AJAX
     */
    public function search()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $posts = $this->postsModel->getPostsWithFilters($perPage, $keyword, $page, $kategori);
        $total = $this->postsModel->getTotalPosts($keyword, $kategori);

        return $this->response->setJSON([
            'success' => true,
            'data' => $posts,
            'total' => $total,
            'currentPage' => $page,
            'perPage' => $perPage
        ]);
    }

    /**
     * Get posts for AJAX requests
     */
    public function getPostsAjax()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;

        $posts = $this->postsModel->getPostsWithFilters($perPage, $keyword, $page, $kategori);
        $total = $this->postsModel->getTotalPosts($keyword, $kategori);

        return $this->response->setJSON([
            'success' => true,
            'data' => $posts,
            'total' => $total,
            'currentPage' => $page,
            'perPage' => $perPage,
            'lastPage' => ceil($total / $perPage)
        ]);
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
