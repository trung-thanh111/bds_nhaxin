# Homepark - Luxury Real Estate

Homepark là một template website bất động sản cao cấp, được thiết kế chuyên biệt cho các dự án khu đô thị, biệt thự và căn hộ hạng sang. Giao diện tập trung vào trải nghiệm người dùng tối ưu hóa (UX) và thiết kế giao diện (UI) hiện đại, tinh tế, mang lại cảm giác xứng tầm đẳng cấp.

## Điểm Nhấn Thiết Kế (Design Philosophy)

Mục tiêu thiết kế của Homepark là tạo ra không gian trải nghiệm "Sang Trọng - Trực Quan - Tinh Tế".

- **Màu sắc chủ đạo**: Nền đen bí ẩn (Dark Theme), kết hợp với yếu tố vàng nhạt (Gold/Beige) tạo điểm nhấn đẳng cấp và nổi bật.
- **Typography**: Sự kết hợp hoàn hảo giữa phông chữ có chân (Serif - Playfair Display) cho các tiêu đề (Heading) để tạo sự sang trọng cổ điển, và phông chữ không chân (Sans-Serif - Montserrat/Lato) cho nội dung (Body text) để đảm bảo độ đọc tốt nhất.
- **Bố cục (Layout)**: Bất đối xứng nhưng hài hòa, không gian trắng (Whitespace) được phân bổ rộng rãi giúp hình ảnh dự án thực sự tỏa sáng.
- **Hiệu ứng (Animations)**: Các hiệu ứng chuyển động nhẹ nhàng khi cuộn trang (Scroll animations) sử dụng thư viện UIkit, mang lại cảm giác mượt mà và sống động.

## Các Trang Tính Năng Chính (Main Pages)

Template Homepark bao gồm hệ thống các trang được thiết kế đồng bộ và chuyên nghiệp:

1. **Trang Chủ (Homepage)**
    - Banner video slide toàn màn hình ấn tượng.
    - Sơ đồ mặt bằng (Floorplans) tích hợp thư viện xem ảnh (Fancybox).
    - Thống kê chi tiết dự án (diện tích, phòng ngủ, phòng tắm...) với bộ đếm động (Counter).
    - Cập nhật tin tức trực quan.

2. **Về Dự Án (About Page)**
    - Bố cục "Luxury Magazine" độc đáo với hình ảnh đan xen nội dung.
    - Timeline giới thiệu hoặc lời ngỏ từ nhà phát triển.
    - Phần Call-to-Action (CTA) nổi bật để người dùng dễ dàng liên hệ.

3. **Tiện Nghi (Amenities Page)**
    - Danh sách các tiện ích nội khu và ngoại khu được trình bày dạng thẻ (Cards) hiện đại.
    - Icon minh họa thanh mảnh, màu vàng đồng bộ.

4. **Xung Quanh (Neighbourhood Page)**
    - Các địa điểm nổi bật lân cận dự án.
    - Hiển thị khoảng cách và thời gian di chuyển, tối ưu hóa cho người mua thực.

5. **Thư Viện Ảnh (Gallery Page)**
    - Bố cục lưới kiểu xếp gạch (Masonry/Mosaic Layout).
    - Phân loại hình ảnh theo chủ đề (Ngoại thất, Nội thất, Tiện ích...).
    - Xem trước hình ảnh toàn màn hình với Fancybox.

6. **Tin Tức (Blog / News)**
    - Giao diện danh sách bài viết (Catalogue) dạng lưới.
    - Sidebar tìm kiếm và phân loại chuyên nghiệp.
    - Trang chi tiết bài viết (Blog Detail) với cấu trúc dễ đọc, chuẩn SEO, có phần bài viết liên quan.

7. **Liên Hệ (Contact Page)**
    - Form liên hệ đặt lịch tư vấn/tham quan thiết kế sang trọng.
    - Bản đồ vị trí văn phòng bán hàng & vị trí dự án.

## Thành Phần Giao Diện (Components)

Ngoài các trang chính, dự án còn tập trung phát triển các thành phần UI dùng chung linh hoạt:

- **Header / Navigation**:
    - **Sticky Header**: Thanh điều hướng tự động cố định ở trên cùng với nền mờ (Dark transparent) khi cuộn.
    - **Mega Menu (Desktop)**: Menu ngang thả xuống.
    - **Off-Canvas (Desktop & Mobile)**: Tích hợp Off-canvas đôi. Desktop Off-canvas hiển thị thông tin nhanh của một dự án nổi bật (Mock data: giá, diện tích, thư viện ảnh). Mobile Off-canvas đóng vai trò là menu điều hướng.
- **Footer**: Tổ chức thông tin liên hệ, menu liên kết và mạng xã hội rõ ràng, sang trọng.
- **Floating Action Buttons**: Nút liên hệ nhanh qua Zalo, Hotline và tính năng "Cuộn lên đầu trang" (Back-to-top) được ghim một cách tinh tế.

## Công Nghệ Sử Dụng (Tech Stack)

Đây là một dự án **Laravel Blade**, tận dụng sức mạnh phía Frontend của các thư viện:

- **Core**: HTML5, Blade Templating Engine.
- **Styling**: Tùy biến sâu từ **UIkit 2**, bổ sung thêm hệ thống CSS riêng (homepark.css).
- **Icons**: FontAwesome 4.
- **Media Viewing**: Fancybox (dành cho thư viện ảnh và video).
- **Carousels**: Swiper.js cho các phần trượt nội dung mượt mà.
