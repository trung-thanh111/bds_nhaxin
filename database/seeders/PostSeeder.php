<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\PostCatalogue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('posts')->truncate();
        DB::table('post_catalogues')->truncate();
        DB::table('post_language')->truncate();
        DB::table('post_catalogue_language')->truncate();
        DB::table('post_catalogue_post')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userId = 4504;
        $languageId = 1; // vi

        // Dữ liệu thật hơn (3 nhóm, mỗi nhóm 2 bài)
        $data = [
            [
                'catalogue' => 'Tin tức thị trường',
                'catalogue_desc' => 'Cập nhật biến động giá, chính sách, xu hướng đầu tư và góc nhìn thị trường.',
                'posts' => [
                    [
                        'title' => 'Giá căn hộ TP.HCM quý này: tín hiệu phục hồi hay chỉ là “nhịp kỹ thuật”?',
                        'excerpt' => 'Thanh khoản nhích lên ở một số phân khúc, nhưng mức độ phục hồi còn phụ thuộc lãi suất, tiến độ pháp lý và nguồn cung mới.',
                        'content' => $this->contentMarket1(),
                        'keywords' => 'giá căn hộ, thị trường bất động sản, TP.HCM, thanh khoản, lãi suất',
                    ],
                    [
                        'title' => 'Kinh nghiệm chọn dự án an toàn pháp lý: 7 giấy tờ cần kiểm tra trước khi xuống tiền',
                        'excerpt' => 'Đừng chỉ nhìn vị trí và giá. Kiểm tra pháp lý giúp bạn tránh rủi ro “kẹt sổ”, chậm bàn giao hoặc tranh chấp sau này.',
                        'content' => $this->contentMarket2(),
                        'keywords' => 'pháp lý dự án, sổ hồng, giấy phép xây dựng, nghiệm thu, mua nhà an toàn',
                    ],
                ],
            ],
            [
                'catalogue' => 'Phong thủy nhà ở',
                'catalogue_desc' => 'Gợi ý bố trí không gian sống hài hòa, tối ưu ánh sáng và dòng khí theo nguyên tắc phổ biến.',
                'posts' => [
                    [
                        'title' => 'Bố trí phòng khách “đón tài”: 5 lỗi hay gặp khiến nhà tối và bí',
                        'excerpt' => 'Chỉ cần điều chỉnh ánh sáng, lối đi và điểm nhấn nội thất, phòng khách có thể thoáng hơn và tạo cảm giác dễ chịu rõ rệt.',
                        'content' => $this->contentFeng1(),
                        'keywords' => 'phong thủy phòng khách, bố trí nội thất, ánh sáng, thông gió, đón tài',
                    ],
                    [
                        'title' => 'Chọn hướng bếp và vị trí đặt bếp: nguyên tắc dễ áp dụng cho nhà phố & chung cư',
                        'excerpt' => 'Không phải ai cũng xoay được hướng bếp theo ý muốn. Bạn có thể ưu tiên các nguyên tắc “tránh” để giảm xung đột và mùi.',
                        'content' => $this->contentFeng2(),
                        'keywords' => 'hướng bếp, vị trí đặt bếp, nhà phố, chung cư, phong thủy bếp',
                    ],
                ],
            ],
            [
                'catalogue' => 'Dự án nổi bật',
                'catalogue_desc' => 'Tổng hợp dự án đáng chú ý theo khu vực, tiện ích, tiến độ và điểm mạnh đầu tư/an cư.',
                'posts' => [
                    [
                        'title' => 'Review nhanh dự án ven sông: lợi thế view và rủi ro bạn cần biết',
                        'excerpt' => 'Dự án ven sông thường có biên độ giá tốt nhờ cảnh quan, nhưng cần lưu ý quy hoạch bờ kè, pháp lý đất và phí vận hành.',
                        'content' => $this->contentProject1(),
                        'keywords' => 'dự án ven sông, review dự án, tiện ích, quy hoạch, phí quản lý',
                    ],
                    [
                        'title' => 'Checklist đi xem nhà mẫu: 12 điểm cần chụp lại để về so sánh',
                        'excerpt' => 'Đi xem nhà mẫu đừng chỉ “xem cho biết”. Có checklist rõ ràng giúp bạn đánh giá vật liệu, diện tích thực và công năng.',
                        'content' => $this->contentProject2(),
                        'keywords' => 'xem nhà mẫu, checklist mua nhà, vật liệu bàn giao, diện tích thông thủy',
                    ],
                ],
            ],
        ];

        $imageIndex = 1;

        foreach ($data as $idx => $group) {
            $catName = $group['catalogue'];

            $catalogue = PostCatalogue::create([
                'parent_id' => 0,
                'lft' => ($idx * 2) + 1,
                'rgt' => ($idx * 2) + 2,
                'level' => 1,
                'publish' => 2,
                'user_id' => $userId,
                'order' => $idx + 1,
            ]);

            $catalogueSlug = Str::slug($catName);

            $catalogue->languages()->attach($languageId, [
                'name' => $catName,
                'canonical' => $catalogueSlug,
                'description' => $group['catalogue_desc'],
                'content' => '<p>' . e($group['catalogue_desc']) . '</p>',
                'meta_title' => $catName . ' | Cập nhật & phân tích',
                'meta_keyword' => $catName,
                'meta_description' => $group['catalogue_desc'],
            ]);

            foreach ($group['posts'] as $order => $p) {
                $title = $p['title'];

                // canonical unique, nhìn “thật” hơn (có hậu tố ngắn)
                $canonical = Str::slug($title) . '-' . Str::lower(Str::random(5));

                $post = Post::create([
                    'post_catalogue_id' => $catalogue->id,
                    'user_id' => $userId,
                    'publish' => 2,
                    'order' => $order + 1,
                    'image' => '/userfiles/image/post-' . $imageIndex . '.jpg',
                ]);

                $imageIndex++;

                $post->languages()->attach($languageId, [
                    'name' => $title,
                    'canonical' => $canonical,
                    'description' => $p['excerpt'],
                    'content' => $p['content'],
                    'meta_title' => $title,
                    'meta_keyword' => $p['keywords'],
                    'meta_description' => $p['excerpt'],
                ]);

                $post->post_catalogues()->attach($catalogue->id);
            }
        }

        $this->command->info('PostSeeder completed: 3 catalogues, 6 posts (realistic content).');
    }

    private function contentMarket1(): string
    {
        return <<<HTML
<h2>Bức tranh chung</h2>
<p>Trong giai đoạn gần đây, mức quan tâm quay lại ở một số khu vực có hạ tầng tốt, nhưng người mua vẫn ưu tiên sản phẩm có pháp lý rõ ràng và tiến độ thi công minh bạch.</p>

<h2>Những yếu tố đang chi phối giá</h2>
<ul>
  <li><strong>Lãi suất & dòng tiền:</strong> lãi vay giảm giúp tâm lý mua ở thực cải thiện.</li>
  <li><strong>Nguồn cung mới:</strong> dự án mở bán ít, khiến giá sơ cấp khó giảm sâu.</li>
  <li><strong>Pháp lý:</strong> dự án rõ giấy tờ vẫn có thanh khoản vượt trội.</li>
</ul>

<h2>Gợi ý cho người mua</h2>
<p>Nếu mua để ở, ưu tiên căn hộ có tiện ích thiết yếu (trường học, y tế, siêu thị) trong bán kính 3–5km. Nếu mua đầu tư, hãy tính kỹ chi phí vốn và kịch bản cho thuê tối thiểu 12–24 tháng.</p>
HTML;
    }

    private function contentMarket2(): string
    {
        return <<<HTML
<h2>Vì sao cần soi pháp lý trước?</h2>
<p>Giá tốt nhưng pháp lý không rõ ràng có thể khiến bạn kẹt sổ hoặc kéo dài thời gian nhận nhà. Kiểm tra trước giúp giảm rủi ro và tăng khả năng vay ngân hàng.</p>

<h2>7 giấy tờ nên kiểm tra</h2>
<ol>
  <li>Quyết định/chấp thuận chủ trương đầu tư (nếu có).</li>
  <li>Giấy tờ về quyền sử dụng đất của dự án.</li>
  <li>Giấy phép xây dựng phù hợp quy mô.</li>
  <li>Văn bản nghiệm thu/đủ điều kiện bán (theo loại hình dự án).</li>
  <li>Hợp đồng mẫu & phụ lục vật liệu bàn giao.</li>
  <li>Tiến độ thanh toán gắn mốc xây dựng.</li>
  <li>Cam kết thời gian ra sổ & điều khoản phạt chậm.</li>
</ol>

<h2>Mẹo nhỏ</h2>
<p>Hãy chụp lại toàn bộ tài liệu/biển thông tin tại sàn, sau đó đối chiếu với tư vấn pháp lý hoặc ngân hàng đang tài trợ dự án.</p>
HTML;
    }

    private function contentFeng1(): string
    {
        return <<<HTML
<h2>Phòng khách thoáng là “điểm cộng” lớn</h2>
<p>Phòng khách là nơi đón khách và cũng là không gian sinh hoạt chính. Cảm giác sáng – thoáng thường quan trọng hơn việc trang trí quá nhiều đồ.</p>

<h2>5 lỗi hay gặp</h2>
<ul>
  <li>Để lối đi bị chắn bởi bàn/đồ trang trí.</li>
  <li>Rèm quá dày khiến thiếu ánh sáng tự nhiên.</li>
  <li>Gương đặt đối diện cửa ra vào gây chói và khó chịu.</li>
  <li>Quá nhiều vật dụng nhỏ làm không gian “rối”.</li>
  <li>Đặt sofa sát cửa sổ nhưng không xử lý nắng nóng.</li>
</ul>

<h2>Cách xử lý nhanh</h2>
<p>Ưu tiên 1–2 điểm nhấn (tranh hoặc đèn), giữ lối đi tối thiểu 80–100cm, dùng rèm 2 lớp để linh hoạt ánh sáng.</p>
HTML;
    }

    private function contentFeng2(): string
    {
        return <<<HTML
<h2>Nguyên tắc dễ áp dụng</h2>
<p>Với nhà phố/chung cư, bạn có thể không đổi được hướng bếp. Khi đó, hãy ưu tiên tránh các vị trí “xung” cơ bản để bếp vận hành thuận tiện và sạch.</p>

<h2>Nên tránh</h2>
<ul>
  <li>Bếp đối diện trực tiếp cửa chính.</li>
  <li>Bếp sát nhà vệ sinh hoặc thông gió kém.</li>
  <li>Bếp đặt dưới xà ngang gây cảm giác nặng nề.</li>
</ul>

<h2>Ưu tiên</h2>
<p>Khoảng cách bếp – chậu rửa hợp lý, có hút mùi tốt, và mặt bếp dễ lau chùi. Nếu không đủ diện tích, dùng vách/đảo bếp nhỏ để phân tách khu nấu.</p>
HTML;
    }

    private function contentProject1(): string
    {
        return <<<HTML
<h2>Vì sao dự án ven sông được quan tâm?</h2>
<p>View thoáng và mảng xanh giúp giá trị ở thực cao, đồng thời tạo lợi thế cho thuê. Tuy nhiên, yếu tố quy hoạch và vận hành rất quan trọng.</p>

<h2>Điểm cần kiểm tra</h2>
<ul>
  <li><strong>Quy hoạch bờ kè:</strong> lộ giới, hành lang bảo vệ sông.</li>
  <li><strong>Tiện ích thực tế:</strong> công viên ven sông có mở cho cư dân hay công cộng?</li>
  <li><strong>Phí quản lý:</strong> dự án cao cấp thường phí cao, ảnh hưởng dòng tiền.</li>
</ul>

<h2>Kết luận</h2>
<p>Nếu mua để ở: ưu tiên block có view thoáng nhưng không quá sát khu công cộng ồn. Nếu đầu tư: cần tính mức thuê kỳ vọng và thời gian lấp đầy.</p>
HTML;
    }

    private function contentProject2(): string
    {
        return <<<HTML
<h2>Vì sao cần checklist khi đi xem nhà mẫu?</h2>
<p>Nhà mẫu thường được “tối ưu” ánh sáng và bố trí. Checklist giúp bạn so sánh công năng và chất lượng bàn giao một cách khách quan.</p>

<h2>12 điểm nên chụp lại</h2>
<ul>
  <li>Mặt bằng căn hộ + vị trí căn trên tầng.</li>
  <li>Chiều cao trần, kích thước phòng ngủ.</li>
  <li>Danh mục vật liệu bàn giao (sàn, tủ bếp, thiết bị vệ sinh).</li>
  <li>Logia/ban công: diện tích và hướng nắng.</li>
  <li>Hệ cửa, kính, khóa, chống ồn.</li>
  <li>Hệ thống điện – nước và vị trí ổ cắm.</li>
  <li>Vị trí cục nóng điều hòa và thoát nước.</li>
  <li>Tiện ích tầng trệt và lối vào sảnh.</li>
  <li>Bãi xe: diện tích, lối lên xuống.</li>
  <li>Phí quản lý dự kiến.</li>
  <li>Tiến độ thi công dự kiến.</li>
  <li>Điều khoản thanh toán và ưu đãi.</li>
</ul>

<h2>Mẹo cuối</h2>
<p>Về nhà, bạn chấm điểm từng tiêu chí (1–5) để ra quyết định nhanh và tránh bị “mood” tại sự kiện mở bán tác động.</p>
HTML;
    }
}
