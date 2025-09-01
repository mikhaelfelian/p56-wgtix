<?php
// TransJualPlatModel (returnType=object)
namespace App\Models;

use CodeIgniter\Model;

class TransJualPlatModel extends Model
{
    protected $table            = 'tbl_trans_jual_plat';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_penjualan',
        'id_platform',
        'no_nota',
        'platform',
        'nominal',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
}

?>