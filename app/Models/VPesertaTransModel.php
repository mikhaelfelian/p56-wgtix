<?php

namespace App\Models;

use CodeIgniter\Model;

class VPesertaTransModel extends Model
{
    protected $table            = 'v_peserta_trans';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';

    // Optionally, you may want to specify which columns are selected by default
    protected $selectFields = [
        'd.id',
        'j.invoice_no',
        'j.invoice_date',
        'p.created_at AS paid_date',
        'd.sort_num',
        'd.item_data',
        'd.unit_price',
        'd.total_price',
        'j.payment_status',
    ];
}
