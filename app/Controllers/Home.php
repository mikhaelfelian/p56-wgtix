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
use App\Models\VPesertaTransModel;
use App\Models\TransJualDetModel;

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

    public function test()
    {
        $vPesertaTransModel = new VPesertaTransModel();
        $transJualDetModel = new TransJualDetModel();
        
        // Get all records from v_peserta_trans where paid_date IS NOT NULL, ordered by paid_date ASC
        $records = $vPesertaTransModel
            ->where('paid_date IS NOT', null)
            ->orderBy('paid_date', 'ASC')
            ->findAll();
        
        $updated = 0;
        $errors = [];
        $sortNum = 1;
        
        foreach ($records as $record) {
            try {
                // Update sort_num in tbl_trans_jual_det using the id from the view
                // The view's 'd.id' corresponds to tbl_trans_jual_det.id
                $success = $transJualDetModel->update($record->id, [
                    'sort_num' => $sortNum
                ]);
                
                if ($success) {
                    $updated++;
                    $sortNum++;
                } else {
                    $errors[] = "Failed to update record ID: {$record->id}";
                }
            } catch (\Exception $e) {
                $errors[] = "Error updating record ID {$record->id}: " . $e->getMessage();
            }
        }
        
        $result = [
            'success' => true,
            'total_records' => count($records),
            'updated' => $updated,
            'next_sort_num' => $sortNum,
            'errors' => $errors
        ];
        
        return $this->response->setJSON($result);
    }
}
