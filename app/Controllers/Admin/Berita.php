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
        $page = $this->request->getGet('page') ?? 1;
        $keyword = $this->request->getGet('keyword') ?? '';
        $kategori = $this->request->getGet('kategori') ?? '';
        $perPage = $this->pengaturan->pagination_limit ?? 10;

        // Get posts with pagination and search (for admin, show all statuses)
        $posts = $this->postsModel->getPostsWithFilters($perPage, $keyword, $page, $kategori, null, false);
        $total = $this->postsModel->getTotalPosts($keyword, $kategori, null, false);

        // Debug: Log the data
        log_message('debug', 'Posts count: ' . count($posts));
        log_message('debug', 'Total posts: ' . $total);
        log_message('debug', 'Page: ' . $page . ', PerPage: ' . $perPage);

        // Create pager
        $pager = service('pager');
        $pager->setPath('admin/berita');
        $pager->makeLinks($total, $perPage, $page, 'adminlte_pagination');

        // Get categories for filter
        $categories = $this->categoryModel->where('is_active', 1)->findAll();

        $data = [
            'title'        => 'Data Berita',
            'posts'        => $posts,
            'categories'   => $categories,
            'pager'        => $pager,
            'currentPage'  => $page,
            'perPage'      => $perPage,
            'keyword'      => $keyword,
            'kategori'     => $kategori,
            'total'        => $total,
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita/index', $data);
    }

    public function create()
    {
        // Get categories and format for dropdown
        $categories = $this->categoryModel->where('is_active', '1')->findAll();
        $categoryOptions = ['' => 'Pilih Kategori'];
        foreach ($categories as $category) {
            $categoryOptions[$category->id] = $category->nama;
        }

        $data = [
            'title'           => 'Tambah Berita Baru',
            'categories'      => $categories,
            'categoryOptions' => $categoryOptions,
            'Pengaturan'      => $this->pengaturan,
            'user'            => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita/create', $data);
    }

    public function store()
    {
        // Get post ID for edit mode
        $postId = $this->request->getPost('id');
        $isEdit = !empty($postId);

        // Validation rules
        $rules = [
            'judul'        => 'required|min_length[3]|max_length[240]',
            'konten'       => 'required',
            'status'       => 'required',
        ];

        // Add 'id' rule for edit mode to avoid LogicException
        if ($isEdit) {
            $rules['id'] = 'required|integer|is_natural_no_zero';
        }

        // // Add unique validation for slug and cover image based on mode
        // if (!$isEdit) {
        //     $rules['slug'] .= '|is_unique[tbl_posts.slug]';
        //     $rules['cover_image'] = 'uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]';
        // } else {
        //     // For edit mode, use the actual post ID in the is_unique rule
        //     $rules['slug'] .= '|is_unique[tbl_posts.slug,id,' . $postId . ']';
        //     $rules['cover_image'] = 'permit_empty|max_size[cover_image,2048]|is_image[cover_image]';
        // }

        if (!$this->validate($rules)) {
            return redirect()->to('admin/berita/edit/'.$postId)->withInput()->with('errors', $this->validator->getErrors());
        }

        // Use variables for each input
        $id_user         = $this->ionAuth->user()->row()->id ?? 1; // Default user ID
        $id_category     = $this->request->getPost('id_category');
        $judul           = $this->request->getPost('judul');
        $slug            = $this->request->getPost('slug');
        $excerpt         = $this->request->getPost('excerpt');
        $konten          = $this->request->getPost('konten');
        $status          = $this->request->getPost('status');
        $meta_title      = $this->request->getPost('meta_title');
        $meta_description= $this->request->getPost('meta_description');
        $meta_keywords   = $this->request->getPost('meta_keywords');
        $published_at    = $status === 'published' ? date('Y-m-d H:i:s') : null;

        // Prepare post data
        $postData = [
            'id_user'         => $id_user,
            'id_category'     => $id_category,
            'judul'           => $judul,
            'slug'            => $slug,
            'excerpt'         => $excerpt,
            'konten'          => $konten,
            'status'          => $status,
            'published_at'    => $published_at,
            'meta_title'      => $meta_title,
            'meta_description'=> $meta_description,
            'meta_keywords'   => $meta_keywords
        ];

        // Add ID for edit mode
        if ($isEdit) {
            $postData['id'] = $postId;
        }

        // Handle cover image upload
        $coverImage = $this->request->getFile('cover_image');
        if ($coverImage && $coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = 'cover_' . $coverImage->getRandomName();
            $coverImagePath = FCPATH . '/file/posts/' . ($isEdit ? $postId : 'temp') . '/';
            
            if (!is_dir($coverImagePath)) {
                mkdir($coverImagePath, 0755, true);
            }

            if ($coverImage->move($coverImagePath, $coverImageName)) {
                $postData['cover_image'] = $coverImageName;
            }
        }

        // Save post using save() method
        if ($this->postsModel->save($postData)) {
            $savedPostId = $isEdit ? $postId : $this->postsModel->getInsertID();
            
            // If it's a new post and we have a cover image, move it to the correct directory
            if (!$isEdit && isset($postData['cover_image'])) {
                $tempPath = FCPATH . '/file/posts/temp/' . $postData['cover_image'];
                $finalPath = FCPATH . '/file/posts/' . $savedPostId . '/';
                
                if (!is_dir($finalPath)) {
                    mkdir($finalPath, 0755, true);
                }
                
                if (file_exists($tempPath)) {
                    rename($tempPath, $finalPath . $postData['cover_image']);
                    rmdir(FCPATH . '/file/posts/temp');
                }
            }

            $message = $isEdit ? 'Berita berhasil diupdate' : 'Berita berhasil ditambahkan';
            return redirect()->to('admin/berita')->with('success', $message);
        }

        $message = $isEdit ? 'Gagal mengupdate berita' : 'Gagal menambahkan berita';
        return redirect()->back()->withInput()->with('error', $message);
    }

    public function edit($id)
    {
        $post = $this->postsModel->find($id);

        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Get categories and format for dropdown
        $categories = $this->categoryModel->where('is_active', '1')->findAll();
        $categoryOptions = ['' => 'Pilih Kategori'];
        foreach ($categories as $category) {
            $categoryOptions[$category->id] = $category->nama;
        }

        $data = [
            'title'           => 'Edit Berita',
            'post'            => $post,
            'categories'      => $categories,
            'categoryOptions' => $categoryOptions,
            'Pengaturan'      => $this->pengaturan,
            'user'            => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita/create', $data);
    }

    public function delete($id)
    {
        $post = $this->postsModel->find($id);
        
        if (!$post) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Delete cover image
        if ($post->cover_image && file_exists(FCPATH . '/file/posts/' . $id . '/' . $post->cover_image)) {
            unlink(FCPATH . '/file/posts/' . $id . '/' . $post->cover_image);
        }

        // Delete gallery images (gallery is in the same folder as posts)
        $gallery = $this->galeriModel->where('id_post', $id)->findAll();
        foreach ($gallery as $image) {
            $galleryImagePath = FCPATH . '/file/posts/gallery/' . $id . '/' . $image->filename;
            if (file_exists($galleryImagePath)) {
                unlink($galleryImagePath);
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
        $berita = $this->postsModel->find($id);

        if (!$berita) {
            return redirect()->to('admin/berita')->with('error', 'Berita tidak ditemukan');
        }

        $galleries = $this->galeriModel
            ->where('id_post', $id)
            ->orderBy('urutan', 'ASC')
            ->findAll();

        $data = [
            'title'        => 'Galeri Berita: ' . $berita->judul,
            'berita'       => $berita,
            'galleries'    => $galleries,
            'Pengaturan'   => $this->pengaturan,
            'user'         => $this->ionAuth->user()->row(),
        ];

        return $this->view($this->theme->getThemePath() . '/admin/berita/gallery', $data);
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
            // Create directory structure for this post
            $uploadPath = FCPATH . '/file/posts/gallery/' . $id;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($images as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageName = $image->getRandomName();
                    $image->move($uploadPath, $imageName);

                    $galeriData = [
                        'id_post' => $id,
                        'path' => 'file/posts/gallery/' . $id . '/' . $imageName,
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
