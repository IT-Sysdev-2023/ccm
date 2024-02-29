<?php

namespace App\Helper;

class ColumnsHelper
{
    public static $columns_ds_tagging = [
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
    public static $pdc_check_columns = [
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
    public static $dated_check_columns = [
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
    public static $check_for_clearing_columns = [

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
    public static $leasing_checks_columns = [

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

    public static $pdc_check_clearing_column = [
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
    public static $datedPdcReportTableColumn = [
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
    public static $check_manual_column = [
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
    public static $merge_checks_column = [
        [
            'title' => "",
            'key' => "check_box",
            'align' => "center",
            'width' => '5%'
        ],
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
    public static $bounced_checks_columns = [
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
            'title' => "Bounce Date",
            'dataIndex' => "check_received",
            'key' => "checks_r",
        ],

        [
            'title' => "Customer Name",
            'dataIndex' => "fullname",
            'key' => "address",
        ],
        [
            'title' => "Account Name",
            'dataIndex' => "fullname",
            'key' => "address",
        ],
        [
            'title' => "Account Number",
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
            'width' => "9%"
        ],
    ];
    public static $check_replace_columns = [
        [
            'title' => "Replacement date",
            'dataIndex' => "check_received",
            'key' => "checks_r",
            'width' => "10%"
        ],
        [
            'title' => "Checkreceived",
            'dataIndex' => "check_received",
            'key' => "checks_r",
            'width' => "10%"
        ],
        [
            'title' => "Checkdate",
            'dataIndex' => "check_date",
            'key' => "check_date",
            'width' => "10%"
        ],

        [
            'title' => "Customer Name",
            'dataIndex' => "fullname",
            'key' => "address",
            'width' => "20%"
        ],

        [
            'title' => "Check Number",
            'dataIndex' => "check_no",
            'key' => "check_no",
            'width' => "10%"
        ],
        [
            'title' => "Amount",
            'dataIndex' => "check_amount",
            'key' => "check_a",
            'width' => "8%"
        ],
        [
            'title' => "Mode",
            'dataIndex' => "",
            'key' => "check_a",
            'width' => "6%"
        ],
        [
            'title' => "User",
            'dataIndex' => "",
            'key' => "check_a",
            'width' => "20%"
        ],


        [
            'title' => "Actions",
            'key' => "action",
            'align' => "center",
            'width' => "9%"
        ],
    ];

}