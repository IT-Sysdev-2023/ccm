<?php

namespace App\Helper;

class ColumnsHelper
{
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
    public $pdc_check_columns = [
        [
            'title' => 'Customer Name',
            'dataIndex' => 'fullname',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '30%',
        ],
        [
            'title' => 'Check Number',
            'dataIndex' => 'check_no',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Check Date',
            'dataIndex' => 'check_date',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Action',
            'key' => 'action',
            'ellipsis' => true,
            'width' => '10%',
            'align' => 'center',
        ],
    ];
    public $dated_check_columns = [
        [
            'title' => 'Customer Name',
            'dataIndex' => 'fullname',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '55%',
        ],
        [
            'title' => 'Check Number',
            'dataIndex' => 'check_no',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Check Date',
            'dataIndex' => 'check_date',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Action',
            'key' => 'action',
            'ellipsis' => true,
            'width' => '10%',
            'align' => 'center',
        ],
    ];
    public $check_for_clearing_columns = [

        [
            'title' => '',
            'key' => 'check_box',
            'ellipsis' => true,
            'width' => '6%',
            'align' => 'center'
        ],
        [
            'title' => 'Check Received',
            'dataIndex' => 'check_received',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Check Date',
            'dataIndex' => 'check_date',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Payee / Endorser',
            'dataIndex' => 'fullname',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '45%',
        ],
        [
            'title' => 'Check Number',
            'dataIndex' => 'check_no',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Details',
            'key' => 'details',
            'ellipsis' => true,
            'width' => '10%',
            'align' => 'center',
        ],
    ];
    public $leasing_checks_columns = [

        [
            'title' => '',
            'key' => 'check_box',
            'ellipsis' => true,
            'width' => '6%',
            'align' => 'center'
        ],
        [
            'title' => 'Check Received',
            'dataIndex' => 'check_received',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Check Date',
            'dataIndex' => 'check_date',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Payee / Endorser',
            'dataIndex' => 'fullname',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '45%',
        ],
        [
            'title' => 'Check Number',
            'dataIndex' => 'check_no',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Details',
            'key' => 'details',
            'ellipsis' => true,
            'width' => '10%',
            'align' => 'center',
        ],
    ];

    public $pdc_check_clearing_column = [
        [
            'title' => '',
            'key' => 'check_box',
            'ellipsis' => true,
            'width' => '6%',
            'align' => 'center'
        ],
        [
            'title' => 'Check Received',
            'dataIndex' => 'check_received',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Check Date',
            'dataIndex' => 'check_date',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Payee / Endorser',
            'dataIndex' => 'fullname',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '45%',
        ],
        [
            'title' => 'Check Number',
            'dataIndex' => 'check_no',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Amount',
            'dataIndex' => 'check_amount',
            'key' => 'check_r',
            'ellipsis' => true,
            'width' => '15%',
        ],
        [
            'title' => 'Details',
            'key' => 'details',
            'ellipsis' => true,
            'width' => '10%',
            'align' => 'center',
        ],
    ];
    public $datedPdcReportTableColumn = [
        [
            'title' => "Checkreceived",
            'dataIndex' => "check_received",
            'key' => "checks_r",
        ],
        [
            'title' => "Checkdate",
            'dataIndex' => "check_date",
            'key' => "check_date",
        ],
        [
            'title' => "Bankname",
            'dataIndex' => "bankbranchname",
            'key' => "b_name",
        ],
        [
            'title' => "Payee/Indorser",
            'dataIndex' => "fullname",
            'key' => "address",
        ],
        [
            'title' => "Checkno",
            'dataIndex' => "check_no",
            'key' => "check_no",
        ],
        [
            'title' => "Amount",
            'dataIndex' => "check_amount",
            'key' => "check_a",
        ],
        [
            'title' => "Details",
            'key' => "details",
            'align' => "center",
        ],
    ];
    public $check_manual_column = [
        [
            'title' => "Checkreceived",
            'dataIndex' => "check_received",
            'key' => "checks_r",
        ],
        [
            'title' => "Checkdate",
            'dataIndex' => "check_date",
            'key' => "check_date",
        ],
        [
            'title' => "Check From",
            'dataIndex' => "department",
            'key' => "b_name",
        ],
        [
            'title' => "Customer Name",
            'dataIndex' => "fullname",
            'key' => "address",
        ],
        [
            'title' => "Check Number",
            'dataIndex' => "check_no",
            'key' => "check_no",
        ],
        [
            'title' => "Amount",
            'dataIndex' => "check_amount",
            'key' => "check_a",
        ],
        [
            'title' => "Type",
            'dataIndex' => "type",
            'key' => "type",
            'align' => "center",
        ],
        [
            'title' => "Actions",
            'key' => "action",
            'align' => "center",
        ],
    ];
    public $merge_checks_column = [
        [
            'title' => "Checkreceived",
            'dataIndex' => "check_received",
            'key' => "checks_r",
        ],
        [
            'title' => "Checkdate",
            'dataIndex' => "check_date",
            'key' => "check_date",
        ],

        [
            'title' => "Customer Name",
            'dataIndex' => "fullname",
            'key' => "address",
        ],
        [
            'title' => "Check Number",
            'dataIndex' => "check_no",
            'key' => "check_no",
        ],
        [
            'title' => "Amount",
            'dataIndex' => "check_amount",
            'key' => "check_a",
        ],

        [
            'title' => "Actions",
            'key' => "action",
            'align' => "center",
        ],
    ];

}