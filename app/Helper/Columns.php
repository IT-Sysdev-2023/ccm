<?php

namespace App\Helper;

class Columns{
    public $columns_ds_tagging = [
        [
            'title' => 'Checkreceived',
            'dataIndex' => 'check_received',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '10%',
        ],
        [
            'title' => 'Checkdate',
            'dataIndex' => 'check_date',
            'key' => 'check_d',
            'ellipsis' => true,
            'width' => '10%',

        ],
        [
            'title' => 'Customer',
            'dataIndex' => 'fullname',
            'key' => 'fullname',
            'ellipsis' => true,
            'width' => '30%',
        ],
        [
            'title' => 'Check No',
            'dataIndex' => 'check_no',
            'key' => 'check_no',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_amount',
        ],
        [
            'title' => 'Type',
            'key' => 'type',
            'dataIndex' => 'type',
        ],
        [
            'title' => 'Category',
            'dataIndex' => 'check_category',
            'key' => 'c_cat',

        ],
        [
            'title' => 'Select',
            'key' => 'select',
            'align' => 'center',
        ],
    ];
}