<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PostsModel;
use App\Models\PostsGaleriModel;

/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-31
 * Github: github.com/mikhaelfelian
 * 
 * This file represents the BeritaGallery Controller for managing news gallery images in the admin panel.
 */
class BeritaGallery extends BaseController
{
    protected $postsModel;
    protected $galeriModel;

    public function __construct()
    {
        $this->postsModel = new PostsModel();
        $this->galeriModel = new PostsGaleriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Galeri Berita',
            'posts' => $this->postsModel->select('id, judul, cover_image, created_at')
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll(),
            'gallery' => $this->galeriModel->select('tbl_posts_galeri.*, tbl_posts.judul as post_title')
                                         ->join('tbl_posts', 'tbl_posts.id = tbl_posts_galeri.id_post')
                                         ->orderBy('tbl_posts_galeri.created_at', 'DESC')
                                         ->findAll(),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita-gallery/index', $data);
    }

    public function upload()
    {
        $data = [
            'title' => 'Upload Galeri Berita',
            'posts' => $this->postsModel->select('id, judul')->where('status', 'published')->findAll(),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita-gallery/upload', $data);
    }

    public function store()
    {
        // Disable CSRF for this endpoint since it's used by Dropzone
        $this->request->setGlobal('post', $this->request->getPost());
        
        // Validation rules - simplified for Dropzone
        $rules = [
            'berita_id'  => 'required|integer',
            'gallery'    => 'uploaded[gallery]|max_size[gallery,2048]|ext_in[gallery,jpg,jpeg,png,gif]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Validation failed: ' . implode(', ', $this->validator->getErrors())]);
        }

        // Get post ID from either parameter
        $postId = $this->request->getPost('id_post') ?: $this->request->getPost('berita_id');
        
        // Debug: Check if post ID is received
        if (!$postId) {
            return $this->response->setJSON(['success' => false, 'error' => 'No post ID received. POST data: ' . json_encode($this->request->getPost())]);
        }
        
        $post = $this->postsModel->find($postId);
        
        if (!$post) {
            return $this->response->setJSON(['success' => false, 'error' => 'Berita tidak ditemukan']);
        }

        // Get images from Dropzone (gallery parameter)
        $images = $this->request->getFileMultiple('gallery');
        $uploaded = 0;
        $failed = 0;
        
        // Debug: Log what files are received
        if (empty($images)) {
            // Try single file if multiple files is empty
            $singleFile = $this->request->getFile('gallery');
            if ($singleFile && $singleFile->isValid()) {
                $images = [$singleFile];
            } else {
                $files = $this->request->getFiles();
                $fileInfo = [];
                if ($singleFile) {
                    $fileInfo = [
                        'name' => $singleFile->getName(),
                        'size' => $singleFile->getSize(),
                        'type' => $singleFile->getClientMimeType(),
                        'isValid' => $singleFile->isValid(),
                        'error' => $singleFile->getErrorString()
                    ];
                }
                return $this->response->setJSON(['success' => false, 'error' => 'No images received. Files: ' . json_encode(array_keys($files)) . ' File info: ' . json_encode($fileInfo) . ' POST data: ' . json_encode($this->request->getPost())]);
            }
        }

        if ($images) {
            // Create directory structure for this post
            $uploadPath = FCPATH . '/file/posts/gallery/' . $postId;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($images as $image) {
                // Debug: Check file validation
                if (!$image->isValid()) {
                    return $this->response->setJSON(['success' => false, 'error' => 'File validation failed: ' . $image->getErrorString()]);
                }
                
                if ($image->hasMoved()) {
                    return $this->response->setJSON(['success' => false, 'error' => 'File already moved']);
                }
                
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageName = $image->getRandomName();
                    
                    if ($image->move($uploadPath, $imageName)) {
                        $galeriData = [
                            'id_post' => $postId,
                            'path' => 'file/posts/gallery/' . $postId . '/' . $imageName,
                            'caption' => $this->request->getPost('caption'),
                            'alt_text' => $this->request->getPost('alt_text'),
                            'is_primary' => 0,
                            'urutan' => $this->galeriModel->where('id_post', $postId)->countAllResults() + 1
                        ];

                        // Debug: Log data being inserted
                        if ($this->galeriModel->insert($galeriData)) {
                            $uploaded++;
                        } else {
                            $failed++;
                            // Debug: Log database insert error
                            $errors = $this->galeriModel->errors();
                            return $this->response->setJSON(['success' => false, 'error' => 'Database insert failed: ' . implode(', ', $errors)]);
                        }
                    } else {
                        $failed++;
                        // Debug: Log file move error
                        return $this->response->setJSON(['success' => false, 'error' => 'File move failed: ' . $image->getErrorString()]);
                    }
                }
            }
        }

        $message = "Berhasil upload {$uploaded} gambar";
        if ($failed > 0) {
            $message .= ", {$failed} gambar gagal diupload";
        }

        return $this->response->setJSON(['success' => true, 'message' => $message]);
    }

    public function edit($id)
    {
        $image = $this->galeriModel->find($id);
        
        if (!$image) {
            return redirect()->to('admin/berita-gallery')->with('error', 'Gambar tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Gambar Galeri',
            'image' => $image,
            'post' => $this->postsModel->find($image->id_post),
            'Pengaturan' => $this->getPengaturan()
        ];

        return view('admin-lte-3/admin/berita-gallery/edit', $data);
    }

    public function update($id)
    {
        $image = $this->galeriModel->find($id);
        
        if (!$image) {
            return redirect()->to('admin/berita-gallery')->with('error', 'Gambar tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'caption' => 'permit_empty|max_length[255]',
            'alt_text' => 'permit_empty|max_length[255]',
            'urutan' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare image data
        $imageData = [
            'caption' => $this->request->getPost('caption'),
            'alt_text' => $this->request->getPost('alt_text'),
            'urutan' => $this->request->getPost('urutan') ?? $image->urutan
        ];

        // Update image
        if ($this->galeriModel->update($id, $imageData)) {
            return redirect()->to('admin/berita-gallery')->with('success', 'Gambar berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate gambar');
    }

    public function delete($id)
    {
        // Accept both POST and AJAX (for compatibility)
        if (
            !$this->request->is('post') &&
            !$this->request->isAJAX()
        ) {
            // If not POST or AJAX, show error page (for browser direct access)
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method Not Allowed']);
        }

        $image = $this->galeriModel->find($id);

        if (!$image) {
            // If not found, return JSON for AJAX, or redirect for browser
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gambar tidak ditemukan']);
            } else {
                return redirect()->to('admin/berita-gallery')->with('error', 'Gambar tidak ditemukan');
            }
        }

        // Debug: Log image data
        log_message('debug', 'Delete image data: ' . json_encode($image));

        // Build the correct file path for CI4 (ROOTPATH . 'public/file/posts/...') instead of fcpath
        $filePath = ROOTPATH . 'public/' . $image->path;
        log_message('debug', 'Delete file path: ' . $filePath);
        log_message('debug', 'File exists: ' . (is_file($filePath) ? 'yes' : 'no'));
        
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        // Delete record (soft delete)
        $deleteResult = $this->galeriModel->delete($id);
        log_message('debug', 'Delete result: ' . ($deleteResult ? 'success' : 'failed'));

        if ($deleteResult) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Gambar berhasil dihapus']);
            } else {
                return redirect()->to('admin/berita-gallery')->with('success', 'Gambar berhasil dihapus');
            }
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus gambar']);
            } else {
                return redirect()->to('admin/berita-gallery')->with('error', 'Gagal menghapus gambar');
            }
        }
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak ada gambar yang dipilih']);
        }

        $deleted = 0;
        $failed = 0;

        foreach ($ids as $id) {
            $image = $this->galeriModel->find($id);
            
            if ($image) {
                // Delete file
                if (file_exists(ROOTPATH . 'public/' . $image->path)) {
                    unlink(ROOTPATH . 'public/' . $image->path);
                }

                // Delete record
                if ($this->galeriModel->delete($id)) {
                    $deleted++;
                } else {
                    $failed++;
                }
            }
        }

        $message = "Berhasil menghapus {$deleted} gambar";
        if ($failed > 0) {
            $message .= ", {$failed} gambar gagal dihapus";
        }

        return $this->response->setJSON(['success' => true, 'message' => $message]);
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

    public function getImagesByPost($postId)
    {
        $images = $this->galeriModel->where('id_post', $postId)
                                   ->orderBy('urutan', 'ASC')
                                   ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $images]);
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
