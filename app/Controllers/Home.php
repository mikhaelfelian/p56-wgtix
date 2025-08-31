<?php
/**
 * Home Controller
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Home controller for managing home page and events display
 * This file represents the home controller for the application.
 */

namespace App\Controllers;

use App\Models\EventsModel;

class Home extends BaseController
{
    protected $eventsModel;

    public function __construct()
    {
        $this->eventsModel = new EventsModel();
    }

    public function index()
    {
        // Get active events (status = 1)
        $activeEvents = $this->eventsModel->where('status', 1)
                                         ->where('tgl_masuk >=', date('Y-m-d'))
                                         ->orderBy('tgl_masuk', 'ASC')
                                         ->limit(8)
                                         ->findAll();

        // Get event statistics
        $eventStats = $this->eventsModel->getEventStatistics();

        $data = [
            'title'         => 'Dashboard',
            'Pengaturan'    => $this->pengaturan,
            'user'          => $this->ionAuth->user()->row(),
            'isMenuActive'  => isMenuActive('dashboard') ? 'active' : '',
            'activeEvents'  => $activeEvents,
            'eventStats'    => $eventStats
        ];

        return view($this->theme->getThemePath() . '/home', $data);
    }
}
