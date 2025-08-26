<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQrCodeToTblPeserta20250807172534 extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_peserta', [
            'qr_code' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'QR Code as base64 string (max 200KB)'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_peserta', 'qr_code');
    }
}
