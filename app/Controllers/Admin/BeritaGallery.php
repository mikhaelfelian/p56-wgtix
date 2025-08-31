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
        // Validation rules
        $rules = [
            'id_post' => 'required|integer',
            'images' => 'required',
            'images.*' => 'uploaded[images]|max_size[images,2048]|is_image[images]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postId = $this->request->getPost('id_post');
        $post = $this->postsModel->find($postId);
        
        if (!$post) {
            return redirect()->back()->withInput()->with('error', 'Berita tidak ditemukan');
        }

        $images = $this->request->getFileMultiple('images');
        $uploaded = 0;
        $failed = 0;

        if ($images) {
            foreach ($images as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $imageName = $image->getRandomName();
                    
                    if ($image->move(ROOTPATH . 'public/uploads/berita/gallery', $imageName)) {
                        $galeriData = [
                            'id_post' => $postId,
                            'path' => $imageName,
                            'caption' => $this->request->getPost('caption'),
                            'alt_text' => $this->request->getPost('alt_text'),
                            'is_primary' => 0,
                            'urutan' => $this->galeriModel->where('id_post', $postId)->countAllResults() + 1
                        ];

                        if ($this->galeriModel->insert($galeriData)) {
                            $uploaded++;
                        } else {
                            $failed++;
                        }
                    } else {
                        $failed++;
                    }
                }
            }
        }

        $message = "Berhasil upload {$uploaded} gambar";
        if ($failed > 0) {
            $message .= ", {$failed} gambar gagal diupload";
        }

        return redirect()->to('admin/berita-gallery')->with('success', $message);
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
        $image = $this->galeriModel->find($id);
        
        if (!$image) {
            return redirect()->to('admin/berita-gallery')->with('error', 'Gambar tidak ditemukan');
        }

        // Delete file
        if (file_exists(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path)) {
            unlink(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path);
        }

        // Delete record (soft delete)
        if ($this->galeriModel->delete($id)) {
            return redirect()->to('admin/berita-gallery')->with('success', 'Gambar berhasil dihapus');
        }

        return redirect()->to('admin/berita-gallery')->with('error', 'Gagal menghapus gambar');
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
                if (file_exists(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path)) {
                    unlink(ROOTPATH . 'public/uploads/berita/gallery/' . $image->path);
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
