<?php

/**
 * Alter tbl_trans_jual_det Table Migration
 * 
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-09-01
 * Github: github.com/mikhaelfelian
 * Description: Add missing fields to tbl_trans_jual_det table for event and price information
 * This file represents the AlterTransJualDetTable Migration.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTransJualDetTable extends Migration
{
    public function up()
    {
        // Add only the missing fields to tbl_trans_jual_det
        $fields = [
            'event_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'price_id',
            ],
            'price_description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'event_title',
            ],
        ];

        $this->forge->addColumn('tbl_trans_jual_det', $fields);
    }

    public function down()
    {
        // Remove the added fields
        $this->forge->dropColumn('tbl_trans_jual_det', [
            'event_title',
            'price_description'
        ]);
    }
}