<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTripayColumnsToTblPeserta20250808055800 extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_peserta', [
            'tripay_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'qr_code'
            ],
            'tripay_pay_url' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'tripay_reference'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_peserta', ['tripay_reference', 'tripay_pay_url']);
    }
}
