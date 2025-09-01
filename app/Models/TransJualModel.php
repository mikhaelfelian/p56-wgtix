<?php
namespace App\Models;

use CodeIgniter\Model;

class TransJualModel extends Model
{
    protected $table            = 'tbl_trans_jual';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'invoice_no',
        'user_id',
        'session_id',
        'invoice_date',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_reference',
        'notes',
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