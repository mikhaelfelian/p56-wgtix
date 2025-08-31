<?php
/**
 * Public Events Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Public events controller for frontend event display
 * This file represents the public events controller for the application.
 */

namespace App\Controllers;

use App\Models\EventsModel;
use App\Models\KategoriModel;

class Events extends BaseController
{
    protected $eventsModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $page = $this->request->getGet('page_events') ?? 1;
        $keyword = $this->request->getGet('keyword') ?? '';
        $perPage = $this->pengaturan->pagination_limit;

        $kategori = $this->request->getGet('kategori') ?? null;
        $status = $this->request->getGet('status') ?? null;
        $minPrice = $this->request->getGet('min_price') ?? null;
        $maxPrice = $this->request->getGet('max_price') ?? null;
        $activeOnly = true; // or get from request if needed

        $events = $this->eventsModel->getEventsWithFilters($perPage, $keyword, $page, $kategori, $status, $activeOnly, $minPrice, $maxPrice);
        $totalEvents = $this->eventsModel->getTotalEvents($keyword);
        $categories = $this->kategoriModel->where('status', '1')->findAll();

        $data = [
            'title'         => 'Events',
            'Pengaturan'    => $this->pengaturan,
            'categories'    => $categories,
            'events'        => $events,
            'pager'         => $this->eventsModel->pager,
            'keyword'       => $keyword,
            'totalEvents'   => $totalEvents
        ];

        return view($this->theme->getThemePath() . '/events/index', $data);
    }

    public function detail($id)
    {
        $event = $this->eventsModel->find($id);
        
        if (!$event || $event->status != 1) {
            return redirect()->to('events')->with('error', 'Event not found');
        }

        // Get related events
        $relatedEvents = $this->eventsModel->where('status', 1)
                                           ->where('id !=', $id)
                                           ->where('id_kategori', $event->id_kategori)
                                           ->limit(4)
                                           ->findAll();

        $data = [
            'title'         => $event->event,
            'Pengaturan'    => $this->pengaturan,
            'event'         => $event,
            'relatedEvents' => $relatedEvents
        ];

        return view($this->theme->getThemePath() . '/events/detail', $data);
    }

    public function category($categoryId)
    {
        $category = $this->kategoriModel->find($categoryId);
        
        if (!$category) {
            return redirect()->to('events')->with('error', 'Category not found');
        }

        $page = $this->request->getGet('page_events') ?? 1;
        $perPage = $this->pengaturan->pagination_limit;

        $events = $this->eventsModel->where('status', 1)
                                   ->where('id_kategori', $categoryId)
                                   ->orderBy('tgl_masuk', 'ASC')
                                   ->paginate($perPage, 'events');


        $data = [
            'title'         => 'Events - ' . $category->kategori,
            'Pengaturan'    => $this->pengaturan,
            'events'        => $events,
            'category'      => $category,
            'pager'         => $this->eventsModel->pager
        ];

        return view($this->theme->getThemePath() . '/events/category', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        
        if (empty($keyword)) {
            return redirect()->to('events');
        }

        $events = $this->eventsModel->searchEvents($keyword);

        $data = [
            'title'         => 'Search Results - ' . $keyword,
            'Pengaturan'    => $this->pengaturan,
            'events'        => $events,
            'keyword'       => $keyword
        ];

        return view($this->theme->getThemePath() . '/events/search', $data);
    }
}
