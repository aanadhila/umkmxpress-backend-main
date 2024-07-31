<?php

return [
    'courier_status' => [
        '1' => [
            'label' => 'Belum Diverifikasi',
            'badge' => '<span class="badge badge-light-warning">Belum Diverifikasi</span>'
        ],
        '2' => [
            'label' => 'Diterima',
            'badge' => '<span class="badge badge-light-success">Diterima</span>'
        ],
        '3' => [
            'label' => 'Ditolak',
            'badge' => '<span class="badge badge-light-danger">Ditolak</span>'
        ],
    ],
    'shipment_status' => [
        '1' => [
            'label' => 'Notif',
            'badge' => '<span class="badge badge-light-primary">Notif</span>',
            'note'  => 'Notif'
        ],
        '2' => [
            'label' => 'Pesanan Siap Dipickup',
            'badge' => '<span class="badge badge-light-primary">Pesanan Siap Dipickup</span>',
            'note'  => 'Driver menuju lokasi pengirim untuk melakukan pickup'
        ],
        '3' => [
            'label' => 'Pesanan Dipickup',
            'badge' => '<span class="badge badge-light-success">Dipickup</span>',
            'note'  => 'Pesanan telah dipickup oleh driver'
        ],
        '4' => [
            'label' => 'Diproses di Warehouse',
            'badge' => '<span class="badge badge-light-primary">Diproses di Warehouse</span>',
            'note'  => 'Pesanan diproses di warehouse'
        ],
        '5' => [
            'label' => 'Notif',
            'badge' => '<span class="badge badge-light-primary">Notif</span>',
            'note'  => 'Notif'
        ],
        '6' => [
            'label' => 'Menuju Penerima',
            'badge' => '<span class="badge badge-light-success">Menuju Penerima</span>',
            'note'  => 'Pesanan Menuju lokasi penerima'
        ],
        '7' => [
            'label' => 'Pesanan Tiba Ke Penerima',
            'badge' => '<span class="badge badge-light-success">Pesanan Tiba Ke Penerima</span>',
            'note'  => 'Pesanan telah sampai ke penerima'
        ],
        '8' => [
            'label' => 'Pesanan Selesai',
            'badge' => '<span class="badge badge-light-success">Pesanan Selesai</span>',
            'note'  => 'Pesanan telah selesai'
        ],
        '9' => [
            'label' => 'Dibatalkan',
            'badge' => '<span class="badge badge-light-danger">Dibatalkan</span>',
            'note'  => 'Pesanan telah dibatalkan'
        ],
    ],
    'payment_status' => [
        '1' => [
            'label' => 'Menunggu Pembayaran',
            'badge' => '<span class="badge badge-light-warning">Menunggu Pembayaran</span>'
        ],
        '2' => [
            'label' => 'Lunas',
            'badge' => '<span class="badge badge-light-success">Lunas</span>'
        ],
        '3' => [
            'label' => 'Dibatalkan',
            'badge' => '<span class="badge badge-light-danger">Dibatalkan</span>'
        ],
    ],
    'transaction_type' => [
        '1' => 'DEPOSIT',
        '2' => 'WITHDRAW',
        '3' => 'REFUND',
        '4' => 'SHIPMENT CUT',
    ],
    'transaction_type_label' => [
        '1' => 'Penambahan Saldo',
        '2' => 'Penarikan Saldo',
        '3' => 'Pengembalian Saldo',
        '4' => 'Potongan Pengiriman',
    ]
];
