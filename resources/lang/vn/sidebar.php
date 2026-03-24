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
            'title' => 'Dự án',
            'icon' => 'fa fa-building',
            'name' => ['project'],
            'subModule' => [
                [
                    'title' => 'Nhóm dự án',
                    'route' => 'project/catalogue/index'
                ],
                [
                    'title' => 'Danh sách',
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
                    'title' => 'Danh sách',
                    'route' => 'real/estate/index'
                ],
                [
                    'title' => 'Mặt bằng',
                    'route' => 'floorplan/index'
                ],
            ]
        ],
        [
            'title' => 'Thuộc tính',
            'icon' => 'fa fa-cube',
            'name' => ['attribute', 'attribute_catalogue'],
            'subModule' => [
                [
                    'title' => 'Nhóm thuộc tính',
                    'route' => 'attribute/catalogue/index'
                ],
                [
                    'title' => 'Danh sách',
                    'route' => 'attribute/index'
                ]
            ]
        ],
        [
            'title' => 'Tiện ích',
            'icon' => 'fa fa-wrench',
            'name' => ['amenity', 'amenity_catalogue'],
            'subModule' => [
                [
                    'title' => 'Nhóm tiện ích',
                    'route' => 'amenity/catalogue/index'
                ],
                [
                    'title' => 'Danh sách',
                    'route' => 'amenity/index'
                ]
            ]
        ],
        [
            'title' => 'Nhóm Thành Viên',
            'icon' => 'fa fa-user',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'Nhóm Thành Viên',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => 'Thành Viên',
                    'route' => 'user/index'
                ],
                [
                    'title' => 'Quyền',
                    'route' => 'permission/index'
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
                    'title' => 'Danh sách',
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
                    'title' => 'Nhóm thư viện ảnh',
                    'route' => 'gallery/catalogue/index'
                ],
                [
                    'title' => 'Danh sách',
                    'route' => 'gallery/index'
                ]
            ]
        ],
        [
            'title' => 'Môi giới',
            'icon' => 'fa fa-users',
            'name' => ['agent'],
            'subModule' => [
                [
                    'title' => 'Danh sách',
                    'route' => 'agent/index'
                ],
            ]
        ],
        [
            'title' => 'Liên hệ',
            'icon' => 'fa fa-phone-square',
            'name' => ['contacts'],
            'subModule' => [
                [
                    'title' => 'Danh sách',
                    'route' => 'contact_request/index'
                ]
            ]
        ],
        [
            'title' => 'Menu',
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
