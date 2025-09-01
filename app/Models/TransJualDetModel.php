<?php
// TransJualDetModel (returnType=object)
namespace App\Models;

use CodeIgniter\Model;

class TransJualDetModel extends Model
{
    protected $table            = 'tbl_trans_jual_det';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'id_penjualan',
        'event_id',
        'price_id',
        'event_title',
        'price_description',
        'sort_num',
        'item_data',
        'qrcode',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
}
?>