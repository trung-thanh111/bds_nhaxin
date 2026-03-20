<?php
return [
    'module' => [
        [
            'title' => 'Dashboard',
            'icon' => 'fa fa-th-large',
            'name' => ['dashboard'],
            'route' => 'dashboard/index',
            'class' => 'special'
        ],
        [
            'title' => 'Quản lý Dự án',
            'icon' => 'fa fa-building',
            'name' => ['project'],
            'subModule' => [
                [
                    'title' => 'Nhóm dự án',
                    'route' => 'project/catalogue/index'
                ],
                [
                    'title' => 'Danh sách dự án',
                    'route' => 'project/index'
                ]
            ]
        ],
        [
            'title' => 'Bất Động Sản',
            'icon' => 'fa fa-home',
            'name' => ['real', 'property'],
            'subModule' => [
                [
                    'title' => 'Nhóm Bất Động Sản',
                    'route' => 'real/estate/catalogue/index'
                ],
                [
                    'title' => 'Bất Động Sản',
                    'route' => 'real/estate/index'
                ],
                [
                    'title' => 'Mặt bằng',
                    'route' => 'floorplan/index'
                ],
            ]
        ],
        [
            'title' => 'QL Thuộc Tính BDS',
            'icon' => 'fa fa-cube',
            'name' => ['attribute', 'attribute_catalogue'],
            'subModule' => [
                [
                    'title' => 'Nhóm Thuộc tính',
                    'route' => 'attribute/catalogue/index'
                ],
                [
                    'title' => 'Danh Sách Thuộc tính',
                    'route' => 'attribute/index'
                ]
            ]
        ],
        [
            'title' => 'QL Tiện ích',
            'icon' => 'fa fa-wrench',
            'name' => ['amenity', 'amenity_catalogue'],
            'subModule' => [
                [
                    'title' => 'Nhóm Tiện ích',
                    'route' => 'amenity/catalogue/index'
                ],
                [
                    'title' => 'Danh Sách Tiện ích',
                    'route' => 'amenity/index'
                ]
            ]
        ],
        [
            'title' => 'Bài viết',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Nhóm bài viết',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => 'Bài viết',
                    'route' => 'post/index'
                ],
            ]
        ],
        [
            'title' => 'Thư viện ảnh',
            'icon' => 'fa fa-picture-o',
            'name' => ['gallery', 'gallery_catalogue'],
            'subModule' => [
                [
                    'title' => 'Danh sách',
                    'route' => 'gallery/index'
                ],
                [
                    'title' => 'Nhóm thư viện',
                    'route' => 'gallery/catalogue/index'
                ]
            ]
        ],
        [
            'title' => 'Nhân viên môi giới',
            'icon' => 'fa fa-users',
            'name' => ['agent'],
            'subModule' => [
                [
                    'title' => 'Danh sách nhân viên',
                    'route' => 'agent/index'
                ],
            ]
        ],
        [
            'title' => 'QL Liên Hệ',
            'icon' => 'fa fa-phone-square',
            'name' => ['contacts'],
            'subModule' => [
                [
                    'title' => 'QL Liên Hệ',
                    'route' => 'visit_request/index'
                ]
            ]
        ],
        [
            'title' => 'QL Menu',
            'icon' => 'fa fa-bars',
            'name' => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Menu',
                    'route' => 'menu/index'
                ],
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system/index'
                ],

            ]
        ]
    ],
];
