Schema::create('real_estates', function (Blueprint $table) {
$table->id();

// ----------------------------------------------------------
// NHÓM 1: ĐỊNH DANH & PHÂN LOẠI
// ----------------------------------------------------------
$table->string('code')->unique();
// [STATIC] Mã nội bộ tự sinh: RE-2024-001

$table->string('canonical')->unique();
// [STATIC] SEO-friendly URL (auto-gen từ title lúc tạo)

$table->foreignId('catalogue_id')
->constrained('real_estate_catalogues');
// [STATIC] FK → real_estate_catalogues (apartment, land, villa...)

$table->foreignId('project_id')
->nullable()
->constrained('real_estate_projects'); -> tạm thời không dùng thêm field này -> chư có module project và cũng ck cần tạo truocs
// [STATIC] null nếu là nhà phố / đất riêng lẻ không thuộc dự án

$table->foreignId('agent_id')
->nullable()
->constrained('users');
// [DYNAMIC] Agent/môi giới phụ trách — có thể đổi

// ----------------------------------------------------------
// NHÓM 2: NỘI DUNG
// ----------------------------------------------------------
$table->string('title');
$table->text('description')->nullable(); // Mô tả ngắn (excerpt)
$table->longText('content')->nullable(); // Nội dung đầy đủ (rich text)

// ----------------------------------------------------------
// NHÓM 3: VỊ TRÍ
// Lưu cả code (để filter/index) lẫn name (để hiển thị)
// Giữ cả đơn vị hành chính CŨ & MỚI vì Việt Nam đang sáp nhập
// ----------------------------------------------------------

// --- Hành chính CŨ (trước sáp nhập 2025) ---
$table->string('old_province_code')->nullable(); // ho_chi_minh
$table->string('old_province_name')->nullable(); // TP. Hồ Chí Minh
$table->string('old_district_code')->nullable(); // quan_9
$table->string('old_district_name')->nullable(); // Quận 9
$table->string('old_ward_code')->nullable(); // phuong_long_thanh_my
$table->string('old_ward_name')->nullable(); // Phường Long Thành Mỹ

// --- Hành chính MỚI (sau sáp nhập) ---
$table->string('province_code')->nullable(); // ho_chi_minh
$table->string('province_name')->nullable(); // TP. Hồ Chí Minh
$table->string('district_code')->nullable(); // tp_thu_duc
$table->string('district_name')->nullable(); // TP. Thủ Đức
$table->string('ward_code')->nullable(); // phuong_long_thanh_my
$table->string('ward_name')->nullable(); // Phường Long Thành Mỹ

$table->string('street')->nullable(); // Tên đường / số nhà
$table->text('iframe_map')->nullable(); // Google Maps embed

// ----------------------------------------------------------
// NHÓM 4: GIÁ
// Tách rõ: đơn vị tiền tệ vs kiểu tính giá
// ----------------------------------------------------------
$table->unsignedBigInteger('price')->nullable();
// [DYNAMIC] Giá trị số (VNĐ hoặc USD)

$table->string('price_currency')->default('VND');
// [STATIC] Đơn vị tiền tệ: VND | USD

$table->string('price_type')->default('total'); -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là loai_gia
// [STATIC] Kiểu tính giá:
// total → giá tổng (mua bán)
// per_month → giá/tháng (cho thuê căn hộ, nhà phố)
// per_year → giá/năm (cho thuê đất, mặt bằng)
// per_sqm → giá/m² (đất khu công nghiệp, văn phòng)

$table->string('transaction_type')->default('sale'); -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là loai_giao_dich
// [STATIC] sale | rent | sale_and_rent
// sale_and_rent → 1 tài sản có thể vừa bán vừa cho thuê
// → khi đó sẽ có 2 record price: 1 total + 1 per_month

// ----------------------------------------------------------
// NHÓM 5: THÔNG SỐ CHUNG (áp dụng mọi loại BĐS)
// ----------------------------------------------------------
$table->decimal('area', 10, 2)->nullable();
// [STATIC] Diện tích chính (m²)
// apartment → tim tường | land → đất | villa → sàn XD

$table->unsignedSmallInteger('year_built')->nullable();
// [STATIC] Năm xây dựng / bàn giao

$table->string('ownership_type')->nullable();
// [STATIC] Lâu dài | 50 năm | 99 năm

// ----------------------------------------------------------
// NHÓM 6: ĐẶC ĐIỂM NHÀ Ở
// apartment, villa, townhouse, shophouse
// ----------------------------------------------------------
$table->unsignedTinyInteger('bedrooms')->nullable();
$table->unsignedTinyInteger('bathrooms')->nullable();
$table->string('house_direction')->nullable(); // Hướng nhà -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là huong_nha
$table->string('balcony_direction')->nullable(); // Hướng ban công -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là huong_ban_cong
$table->string('view')->nullable();
// Đặc điểm view vật lý cố định: "Công viên 36ha", "Sông Sài Gòn"
// Đây là STATIC vì là đặc điểm địa lý cố định của căn

// ----------------------------------------------------------
// NHÓM 7: ĐẶC ĐIỂM RIÊNG — CĂN HỘ CHUNG CƯ
// ----------------------------------------------------------
$table->decimal('usable_area', 10, 2)->nullable(); // Diện tích thông thủy
$table->string('block_tower')->nullable(); // Tên block: BV1, S1.01
$table->unsignedSmallInteger('floor')->nullable(); // Tầng căn hộ -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là lau
$table->unsignedSmallInteger('total_floors')->nullable(); // Tổng số tầng tòa
$table->string('apartment_code')->nullable(); // Mã căn: BV1-2501

// ----------------------------------------------------------
// NHÓM 8: ĐẶC ĐIỂM RIÊNG — ĐẤT
// ----------------------------------------------------------
$table->string('land_type')->nullable(); -> data sẽ là seclected load từ attributes lấy ra các thuộc tính có code là loai_dat
// tho_cu | nong_nghiep | thuong_mai | cong_nghiep | khac

$table->decimal('land_width', 8, 2)->nullable(); // Chiều ngang (m)
$table->decimal('land_length', 8, 2)->nullable(); // Chiều dài (m)
$table->decimal('road_frontage', 8, 2)->nullable(); // Mặt tiền đường (m)
$table->decimal('road_width', 8, 2)->nullable(); // Rộng đường trước nhà (m)

// ----------------------------------------------------------
// NHÓM 9: ĐẶC ĐIỂM RIÊNG — VILLA / NHÀ PHỐ
// ----------------------------------------------------------
$table->decimal('land_area', 10, 2)->nullable(); // Diện tích đất riêng (m²)
$table->unsignedTinyInteger('floor_count')->nullable(); // Số tầng (không tính hầm)

// ----------------------------------------------------------
// NHÓM 10: MEDIA
// ----------------------------------------------------------
$table->string('video_url')->nullable(); // YouTube / Vimeo
$table->string('tour_url')->nullable(); // Matterport / 3D tour

// ----------------------------------------------------------
// NHÓM 11: XUẤT BẢN
// ----------------------------------------------------------
$table->boolean('publish')->default(false);

// ----------------------------------------------------------
// NHÓM 12: SEO
// ----------------------------------------------------------
$table->string('meta_title')->nullable();
$table->text('meta_description')->nullable();
$table->string('meta_keyword')->nullable();

// ----------------------------------------------------------
// TIMESTAMPS
// ----------------------------------------------------------
$table->timestamps();
$table->softDeletes();

// và còn một số field dạng quan hệ với các bảng như animaties,..
// nhưng hiện tại chưa cần đang cần phát triên basic module này trước nếu có sẽ thêm vào từ từ sau
chỉ cần làm như trên giuso tôi

Schema::create('amenities', function (Blueprint $table) {
$table->id();
$table->string('name'); // Hồ bơi, Gym, Bệnh viện...
$table->text('description')->nullable(); // Hồ bơi, Gym, Bệnh viện...
$table->unsignedBigInteger('animatie_catalogue_id')->nullable(); // loại tiện ích (nội khu, ngoại khu nhé,.. )
$table->string('code')->unique(); tự gen từ name -> tham khảo attribite dùng cho field code nhé // pool, gym, hospital...
$table->string('icon')->nullable(); // icon class / SVG -> cho chọn như ảnh chứ k nhập class nhé 
$table->string('image')->nullable();
$table->unsignedTinyInteger('sort_order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
});
