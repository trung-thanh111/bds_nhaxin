<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\UserCatalogue;
use Illuminate\Support\Facades\DB;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionsList = [
            ['name' => 'Xem Dashboard', 'canonical' => 'dashboard.index'],
            // user
            ['name' => 'Xem danh sách thành viên', 'canonical' => 'user.index'],
            ['name' => 'Thêm mới thành viên', 'canonical' => 'user.create'],
            ['name' => 'Cập nhật thành viên', 'canonical' => 'user.update'],
            ['name' => 'Xóa thành viên', 'canonical' => 'user.destroy'],

            // user cata
            ['name' => 'Xem danh sách nhóm thành viên', 'canonical' => 'user.catalogue.index'],
            ['name' => 'Thêm mới nhóm thành viên', 'canonical' => 'user.catalogue.create'],
            ['name' => 'Cập nhật nhóm thành viên', 'canonical' => 'user.catalogue.update'],
            ['name' => 'Xóa nhóm thành viên', 'canonical' => 'user.catalogue.destroy'],
            ['name' => 'Phân quyền nhóm thành viên', 'canonical' => 'user.catalogue.permission'],

            // permission
            ['name' => 'Xem danh sách quyền', 'canonical' => 'permission.index'],
            ['name' => 'Thêm mới quyền', 'canonical' => 'permission.create'],
            ['name' => 'Cập nhật quyền', 'canonical' => 'permission.update'],
            ['name' => 'Xóa quyền', 'canonical' => 'permission.destroy'],

            // language
            ['name' => 'Xem danh sách ngôn ngữ', 'canonical' => 'language.index'],
            ['name' => 'Thêm mới ngôn ngữ', 'canonical' => 'language.create'],
            ['name' => 'Cập nhật ngôn ngữ', 'canonical' => 'language.update'],
            ['name' => 'Xóa ngôn ngữ', 'canonical' => 'language.destroy'],
            ['name' => 'Dịch thuật ngôn ngữ', 'canonical' => 'language.translate'],

            // system
            ['name' => 'Cấu hình hệ thống', 'canonical' => 'system.index'],
            ['name' => 'Cập nhật hệ thống', 'canonical' => 'system.update'],
            ['name' => 'Dịch thuật hệ thống', 'canonical' => 'system.translate'],

            // menu
            ['name' => 'Xem danh sách menu', 'canonical' => 'menu.index'],
            ['name' => 'Thêm mới menu', 'canonical' => 'menu.create'],
            ['name' => 'Cập nhật menu', 'canonical' => 'menu.update'],
            ['name' => 'Xóa menu', 'canonical' => 'menu.destroy'],
            ['name' => 'Quản lý menu con', 'canonical' => 'menu.children'],
            ['name' => 'Dịch thuật menu', 'canonical' => 'menu.translate'],

            // slide
            ['name' => 'Xem danh sách slide', 'canonical' => 'slide.index'],
            ['name' => 'Thêm mới slide', 'canonical' => 'slide.create'],
            ['name' => 'Chỉnh sửa slide', 'canonical' => 'slide.edit'],
            ['name' => 'Xóa slide', 'canonical' => 'slide.destroy'],

            // widget
            ['name' => 'Xem danh sách widget', 'canonical' => 'widget.index'],
            ['name' => 'Thêm mới widget', 'canonical' => 'widget.create'],
            ['name' => 'Cập nhật widget', 'canonical' => 'widget.update'],
            ['name' => 'Xóa widget', 'canonical' => 'widget.destroy'],
            ['name' => 'Dịch thuật widget', 'canonical' => 'widget.translate'],

            // post
            ['name' => 'Xem danh sách bài viết', 'canonical' => 'post.index'],
            ['name' => 'Thêm mới bài viết', 'canonical' => 'post.create'],
            ['name' => 'Cập nhật bài viết', 'canonical' => 'post.update'],
            ['name' => 'Xóa bài viết', 'canonical' => 'post.destroy'],

            // post cata
            ['name' => 'Xem danh sách nhóm bài viết', 'canonical' => 'post.catalogue.index'],
            ['name' => 'Thêm mới nhóm bài viết', 'canonical' => 'post.catalogue.create'],
            ['name' => 'Cập nhật nhóm bài viết', 'canonical' => 'post.catalogue.update'],
            ['name' => 'Xóa nhóm bài viết', 'canonical' => 'post.catalogue.destroy'],

            //real estate
            ['name' => 'Xem danh sách Bất Động Sản', 'canonical' => 'realEstate.index'],
            ['name' => 'Thêm mới Bất Động Sản', 'canonical' => 'realEstate.create'],
            ['name' => 'Chỉnh sửa Bất Động Sản', 'canonical' => 'realEstate.edit'],
            ['name' => 'Xóa Bất Động Sản', 'canonical' => 'realEstate.delete'],

            // real estate cata
            ['name' => 'Xem danh sách nhóm Bất Động Sản', 'canonical' => 'real_estate.catalogue.index'],
            ['name' => 'Thêm mới nhóm Bất Động Sản', 'canonical' => 'real_estate.catalogue.create'],
            ['name' => 'Cập nhật nhóm Bất Động Sản', 'canonical' => 'real_estate.catalogue.update'],
            ['name' => 'Xóa nhóm Bất Động Sản', 'canonical' => 'real_estate.catalogue.destroy'],

            // project
            ['name' => 'Xem danh sách dự án', 'canonical' => 'project.index'],
            ['name' => 'Thêm mới dự án', 'canonical' => 'project.create'],
            ['name' => 'Chỉnh sửa dự án', 'canonical' => 'project.edit'],
            ['name' => 'Xóa dự án', 'canonical' => 'project.delete'],

            // project cata
            ['name' => 'Xem danh sách nhóm dự án', 'canonical' => 'project.catalogue.index'],
            ['name' => 'Thêm mới nhóm dự án', 'canonical' => 'project.catalogue.create'],
            ['name' => 'Chỉnh sửa nhóm dự án', 'canonical' => 'project.catalogue.edit'],
            ['name' => 'Xóa nhóm dự án', 'canonical' => 'project.catalogue.delete'],

            // floorplan
            ['name' => 'Xem danh sách mặt bằng', 'canonical' => 'floorplan.index'],
            ['name' => 'Thêm mới mặt bằng', 'canonical' => 'floorplan.create'],
            ['name' => 'Chỉnh sửa mặt bằng', 'canonical' => 'floorplan.edit'],
            ['name' => 'Xóa mặt bằng', 'canonical' => 'floorplan.delete'],

            // amenity
            ['name' => 'Xem danh sách tiện ích', 'canonical' => 'amenity.index'],
            ['name' => 'Thêm mới tiện ích', 'canonical' => 'amenity.create'],
            ['name' => 'Chỉnh sửa tiện ích', 'canonical' => 'amenity.edit'],
            ['name' => 'Xóa tiện ích', 'canonical' => 'amenity.delete'],

            // amenity cata
            ['name' => 'Xem danh sách nhóm tiện ích', 'canonical' => 'amenity.catalogue.index'],
            ['name' => 'Thêm mới nhóm tiện ích', 'canonical' => 'amenity.catalogue.create'],
            ['name' => 'Chỉnh sửa nhóm tiện ích', 'canonical' => 'amenity.catalogue.edit'],
            ['name' => 'Xóa nhóm tiện ích', 'canonical' => 'amenity.catalogue.delete'],

            // Gallery
            ['name' => 'Xem danh sách thư viện ảnh', 'canonical' => 'gallery.index'],
            ['name' => 'Thêm mới thư viện ảnh', 'canonical' => 'gallery.create'],
            ['name' => 'Chỉnh sửa thư viện ảnh', 'canonical' => 'gallery.edit'],
            ['name' => 'Xóa thư viện ảnh', 'canonical' => 'gallery.delete'],

            // gallery cata
            ['name' => 'Xem danh sách nhóm thư viện ảnh', 'canonical' => 'gallery.catalogue.index'],
            ['name' => 'Thêm mới nhóm thư viện ảnh', 'canonical' => 'gallery.catalogue.create'],
            ['name' => 'Chỉnh sửa nhóm thư viện ảnh', 'canonical' => 'gallery.catalogue.edit'],
            ['name' => 'Xóa nhóm thư viện ảnh', 'canonical' => 'gallery.catalogue.delete'],

            // agent
            ['name' => 'Xem danh sách môi giới', 'canonical' => 'agent.index'],
            ['name' => 'Thêm mới môi giới', 'canonical' => 'agent.create'],
            ['name' => 'Cập nhật môi giới', 'canonical' => 'agent.update'],
            ['name' => 'Xóa môi giới', 'canonical' => 'agent.destroy'],

            // contact request
            ['name' => 'Xem danh sách liên hệ', 'canonical' => 'contact_request.index'],
            ['name' => 'Thêm mới liên hệ', 'canonical' => 'contact_request.create'],
            ['name' => 'Cập nhật liên hệ', 'canonical' => 'contact_request.update'],
            ['name' => 'Xóa liên hệ', 'canonical' => 'contact_request.destroy'],

            // attribute
            ['name' => 'Xem danh sách thuộc tính', 'canonical' => 'attribute.index'],
            ['name' => 'Thêm mới thuộc tính', 'canonical' => 'attribute.create'],
            ['name' => 'Cập nhật thuộc tính', 'canonical' => 'attribute.update'],
            ['name' => 'Xóa thuộc tính', 'canonical' => 'attribute.destroy'],

            // attribute cata
            ['name' => 'Xem danh sách nhóm thuộc tính', 'canonical' => 'attribute.catalogue.index'],
            ['name' => 'Thêm mới nhóm thuộc tính', 'canonical' => 'attribute.catalogue.create'],
            ['name' => 'Cập nhật nhóm thuộc tính', 'canonical' => 'attribute.catalogue.update'],
            ['name' => 'Xóa nhóm thuộc tính', 'canonical' => 'attribute.catalogue.destroy'],

            // promotion
            ['name' => 'Xem danh sách khuyến mãi', 'canonical' => 'promotion.index'],
            ['name' => 'Thêm mới khuyến mãi', 'canonical' => 'promotion.create'],
            ['name' => 'Cập nhật khuyến mãi', 'canonical' => 'promotion.update'],
            ['name' => 'Xóa khuyến mãi', 'canonical' => 'promotion.destroy'],

            // pro
            ['name' => 'Xem danh sách sản phẩm', 'canonical' => 'product.index'],
            ['name' => 'Thêm mới sản phẩm', 'canonical' => 'product.create'],
            ['name' => 'Cập nhật sản phẩm', 'canonical' => 'product.update'],
            ['name' => 'Xóa sản phẩm', 'canonical' => 'product.destroy'],

            // pro cata
            ['name' => 'Xem danh sách nhóm sản phẩm', 'canonical' => 'product.catalogue.index'],
            ['name' => 'Thêm mới nhóm sản phẩm', 'canonical' => 'product.catalogue.create'],
            ['name' => 'Cập nhật nhóm sản phẩm', 'canonical' => 'product.catalogue.update'],
            ['name' => 'Xóa nhóm sản phẩm', 'canonical' => 'product.catalogue.destroy'],

            ['name' => 'Xem danh sách Bất Động Sản (Old)', 'canonical' => 'real.estate.index'],
            ['name' => 'Thêm mới Bất Động Sản (Old)', 'canonical' => 'real.estate.create'],
            ['name' => 'Cập nhật Bất Động Sản (Old)', 'canonical' => 'real.estate.update'],
            ['name' => 'Xóa Bất Động Sản (Old)', 'canonical' => 'real.estate.destroy'],
        ];

        $permissionIds = [];

        foreach ($permissionsList as $item) {
            $permission = Permission::updateOrCreate(
                ['canonical' => $item['canonical']],
                ['name' => $item['name']]
            );
            $permissionIds[] = $permission->id;
        }

        // cấp quyền
        $userCatalogue = UserCatalogue::find(1);
        if ($userCatalogue) {
            $userCatalogue->permissions()->sync($permissionIds);
            $this->command->info('Đã cập nhật/đồng bộ ' . count($permissionIds) . ' quyền cho nhóm ' . $userCatalogue->name);
        } else {
            $this->command->error('not found user catalogue (ID = 1)');
        }
    }
}
