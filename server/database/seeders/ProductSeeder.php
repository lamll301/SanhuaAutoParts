<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Image;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    private $faker;
    private $path = 'sanhua/san-pham';
    private $promotionIds;
    private $supplierIds;
    private $unitIds;
    private $categoryIds;

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->promotionIds = Promotion::pluck('id')->toArray();
        $this->supplierIds = Supplier::pluck('id')->toArray();
        $this->unitIds = Unit::pluck('id')->toArray();
        $this->categoryIds = Category::pluck('id')->toArray();
    }

    public function run(): void
    {
        $products = [
            'Van EGR Hyundai Tucson 2.0L đời 2018-2022', 'Van EGR Toyota Land Cruiser V8 động cơ diesel 4.5L', 'Van EGR Honda CRV 1.5 Turbo đời 2020-2023', 'Van EGR Ford Ranger Wildtrak 2.0L Bi-Turbo', 'Van EGR Mazda CX-5 2.5L Skyactiv-G Premium',
            'Van PCV Honda Civic 1.5L VTEC Turbo RS', 'Van PCV Toyota Camry 2.5Q hybrid động cơ A25A-FXS', 'Van PCV Mitsubishi Xpander 1.5L MIVEC đời 2020', 'Van PCV Hyundai Santa Fe 2.2L CRDi dầu cao cấp', 'Van PCV Kia Sorento Signature 2.2D máy dầu',
            'Van hằng nhiệt Toyota Innova Cross 2.0L hybrid đời 2023', 'Van hằng nhiệt Lexus LX 570 V8 5.7L cao cấp', 'Van hằng nhiệt BMW X5 xDrive40i động cơ B58', 'Van hằng nhiệt Mercedes C300 AMG Line W206 đời 2022', 'Van hằng nhiệt Audi Q5 45 TFSI quattro S-line',
            'Van điều áp nhiên liệu Mazda 3 2.0L Skyactiv-G Premium', 'Van điều áp nhiên liệu Ford Everest Titanium 2.0L', 'Van điều áp nhiên liệu Nissan Terra 2.5L V4 VL 4WD', 'Van điều áp nhiên liệu Isuzu D-Max 3.0 Ddi BluePower', 'Van điều áp nhiên liệu Suzuki XL7 1.5L GLX',
            'Bugi NGK Laser Iridium Toyota Hilux Revo 2.8 GD', 'Bugi NGK Ruthenium HX Mazda BT-50 3.2L Diesel', 'Bugi NGK G-Power Platinum Honda Brio 1.2L i-VTEC', 'Bugi NGK LPG Laser Line Mitsubishi Outlander 2.4 MIVEC', 'Bugi NGK Racing Hyundai Veloster N 2.0T',
            'Bugi Denso Iridium TT Honda HR-V 1.8L i-VTEC', 'Bugi Denso Twin Tip Toyota Rush 1.5L Dual VVT-i', 'Bugi Denso Platinum Mitsubishi Pajero Sport 2.4D MIVEC', 'Bugi Denso Iridium Power Lexus RX350 V6 3.5L', 'Bugi Denso Long Life Subaru Forester 2.0i-S EyeSight',
            'Dây curoa tổng Toyota Fortuner 2.7L đời 2017-2022', 'Dây curoa tổng Honda Accord 2.4L động cơ K24', 'Dây curoa tổng Nissan Navara NP300 2.5L dầu', 'Dây curoa tổng Chevrolet Colorado High Country 2.5L', 'Dây curoa tổng Peugeot 5008 1.6L THP turbo',
            'Dây curoa cam Volkswagen Tiguan Allspace 2.0 TSI', 'Dây curoa cam Volvo XC60 T8 Twin Engine Hybrid', 'Dây curoa cam Mitsubishi Triton Athlete 2.4L MIVEC', 'Dây curoa cam Ford Ranger Raptor 2.0L Bi-Turbo', 'Dây curoa cam Kia Carnival 2.2L CRDi Signature',
            'Dầu động cơ Castrol EDGE 5W-30 Advanced Fully Synthetic', 'Dầu động cơ Castrol Magnatec Stop-Start 5W-30 A5', 'Dầu động cơ Castrol GTX ULTRACLEAN 10W-40 A3/B4', 'Dầu động cơ Castrol Power 1 Racing 4T 10W-50', 'Dầu động cơ Castrol CRB Turbo G3 15W-40 CI-4/SL',
            'Dầu động cơ Mobil 1 ESP Formula 5W-30 Advanced Synthetic', 'Dầu động cơ Mobil Super 3000 X1 Formula FE 5W-30', 'Dầu động cơ Mobil Delvac MX ESP 15W-40 Diesel', 'Dầu động cơ Mobil Super 2000 X2 10W-40 Semi Synthetic', 'Dầu động cơ Mobil Delvac City Logistics P 5W-30',
            'Gạt mưa Bosch Aerotwin Multi-Clip Toyota Land Cruiser Prado', 'Gạt mưa Bosch Clear Advantage Ford Ranger XLS AT 4x2', 'Gạt mưa Bosch Aerofit Frameless Honda CRV L 1.5 Turbo', 'Gạt mưa Bosch Evolution Beam Hyundai Tucson 2.0 Premium', 'Gạt mưa Bosch Icon Wiper Blade Mazda CX-8 2.5 Premium AWD',
            'Lọc gió động cơ K&N Toyota RAV4 2.5L Hybrid Adventure', 'Lọc gió động cơ Mann-Filter Honda HRV RS 1.5 Turbo', 'Lọc gió động cơ Sakura Mitsubishi Pajero Sport GT Premium 4x4', 'Lọc gió động cơ Denso Hyundai Palisade 3.8L V6 Exclusive', 'Lọc gió động cơ Bosch Kia Sportage 2.0 Signature AWD',
            'Lọc dầu động cơ Mobil 1 Ford Everest 2.0L Bi-Turbo Titanium', 'Lọc dầu động cơ Toyota Genuine Camry 2.5Q 2022-2023', 'Lọc dầu động cơ Mahle BMW X1 sDrive18i xLine Edition', 'Lọc dầu động cơ WIX Mercedes Benz GLC 300 4MATIC AMG', 'Lọc dầu động cơ Fram Ultra Synthetic Audi Q3 Sportback 40 TFSI',
            'Lọc nhiên liệu diesel Fleetguard Toyota Hilux 2.8G 4x4 AT', 'Lọc nhiên liệu diesel Racor Ford Ranger Wildtrak 2.0L 4x4 AT', 'Lọc nhiên liệu diesel Sakura Isuzu MU-X 1.9L Diesel Premium', 'Lọc nhiên liệu diesel Baldwin Mitsubishi Pajero Sport 2.4D MIVEC', 'Lọc nhiên liệu diesel Cummins Mazda BT-50 1.9L MT 4x2',
            'Phụ kiện đèn pha LED Philips Ultinon Pro9000 cho Toyota Fortuner', 'Phụ kiện đèn pha LED Osram Night Breaker H7 cho Ford Ranger', 'Phụ kiện đèn pha LED PIAA Hyper Arros cho Honda CRV', 'Phụ kiện đèn pha LED Lazer Triple-R 750 cho Nissan Terra', 'Phụ kiện đèn pha LED HELLA Performance cho Mazda BT-50',
            'Bình ắc quy Amaron Pro 95D31R cho Toyota Land Cruiser', 'Bình ắc quy GS Premium N120 cho Mercedes GLS 450 4MATIC', 'Bình ắc quy Bosch S5 A13 AGM cho BMW 530i M Sport', 'Bình ắc quy Varta Blue Dynamic E11 cho Audi Q7 3.0 TFSI', 'Bình ắc quy Delkor LN3 (90D23L) cho Lexus ES 350',
            'Phanh đĩa trước Brembo Gran Turismo cho Subaru WRX STI', 'Phanh đĩa trước ATE PowerDisc cho Volkswagen Tiguan Allspace', 'Phanh đĩa trước Ferodo Premier cho Mazda 6 2.5L Premium', 'Phanh đĩa trước EBC Brakes USR Series cho Honda Civic Type R', 'Phanh đĩa trước DBA 4000 Series T3 Slotted cho Ford Mustang GT',
            'Má phanh sau Akebono Euro Ultra-Premium Ceramic cho Lexus NX 300', 'Má phanh sau Textar epad cho BMW X3 xDrive20i xLine', 'Má phanh sau TRW cho Mercedes C200 Avantgarde', 'Má phanh sau Bendix General CT cho Toyota Alphard 2.5L', 'Má phanh sau Jurid Premium Quality cho Volvo S60 T5 R-Design',
            'Giảm xóc KYB Excel-G cho Honda City RS 1.5L Turbo', 'Giảm xóc Bilstein B6 Performance cho Ford Everest Titanium Plus', 'Giảm xóc Monroe Original cho Hyundai Creta 1.5L Premium', 'Giảm xóc Tokico D-Spec cho Suzuki Swift Sport', 'Giảm xóc Sachs Advantage cho Kia Carnival 2.2L CRDi',
            'Thanh giằng cân bằng Whiteline cho Subaru Forester 2.0i-S EyeSight', 'Thanh giằng cân bằng Hardrace cho Honda Civic 1.5L RS', 'Thanh giằng cân bằng Cusco cho Toyota GR Yaris 1.6L Turbo', 'Thanh giằng cân bằng Megan Racing cho Mazda 3 2.0L Premium', 'Thanh giằng cân bằng Ultra Racing cho Hyundai Kona 1.6T',
            'Càng A Ford Ranger Raptor phiên bản 2023 chính hãng', 'Càng A Toyota Land Cruiser 300 VX-R hàng nhập khẩu', 'Càng A Mitsubishi Pajero Sport GT Premium 4WD chính hãng', 'Càng A Isuzu D-Max V-Cross 3.0 Prestige 4x4 nhập khẩu', 'Càng A Nissan Navara Pro-4X 2.5L YD25 hàng chính hãng',
            'Rotuyn lái ngoài Lemförder cho Mercedes GLE 450 4MATIC Coupe', 'Rotuyn lái ngoài 555 cho Toyota Vios 1.5G CVT 2023', 'Rotuyn lái ngoài Febi Bilstein cho BMW X5 xDrive40i M Sport', 'Rotuyn lái ngoài CTR cho Hyundai Santa Fe 2.5L Premium', 'Rotuyn lái ngoài MOOG cho Ford Territory Titanium 1.5L EcoBoost',
            'Cao su chân máy Hutchinson cho Peugeot 3008 GT Line 1.6L Turbo', 'Cao su chân máy Febest cho Honda Accord 1.5L Turbo', 'Cao su chân máy Corteco cho Mazda CX-8 2.5L Premium AWD', 'Cao su chân máy Lemförder cho Audi A4 2.0 TFSI', 'Cao su chân máy OEM cho Toyota Corolla Cross 1.8 HV',
            'Phụ tùng hộp số tự động ZF cho BMW Series 5 530i M Sport', 'Phụ tùng hộp số tự động AISIN cho Toyota Camry 2.5HV đời 2023', 'Phụ tùng hộp số tự động JATCO cho Nissan X-Trail 2.0L Premium', 'Phụ tùng hộp số tự động Allison cho Chevrolet Silverado HD 2500', 'Phụ tùng hộp số tự động Magna Powertrain cho Mercedes GLC 300 AMG',
            'Dầu hộp số tự động AMSOIL Signature Series cho Lexus LX 600', 'Dầu hộp số tự động Liqui Moly Top Tec cho Audi Q7 3.0 TFSI', 'Dầu hộp số tự động Pentosin cho Volkswagen Teramont 2.0 TSI', 'Dầu hộp số tự động Ravenol J2/S cho BMW X7 xDrive40i', 'Dầu hộp số tự động Motul DCTF cho Mercedes A250 AMG Line',
            'Gioăng đại tu động cơ Ajusa cho Mitsubishi Triton Athlete', 'Gioăng đại tu động cơ Victor Reinz cho Ford Ranger XLT Limited', 'Gioăng đại tu động cơ Elring cho Mercedes E300 AMG Line', 'Gioăng đại tu động cơ Nippon Leakless cho Toyota Hilux 2.8G', 'Gioăng đại tu động cơ Corteco cho BMW X3 xDrive20i xLine',
            'Xi lanh phanh chính ATE cho Volkswagen Tiguan Allspace', 'Xi lanh phanh chính TRW cho Hyundai Tucson 2.0D Signature', 'Xi lanh phanh chính ADVICS cho Toyota Land Cruiser Prado', 'Xi lanh phanh chính Valeo cho Peugeot 5008 1.6L THP', 'Xi lanh phanh chính Akebono cho Honda Odyssey 2.4L',
            'Bơm nước làm mát GMB cho Honda CRV 1.5 Turbo đời 2021', 'Bơm nước làm mát AISIN cho Toyota Fortuner 2.8V 4x4 AT', 'Bơm nước làm mát Hepu cho BMW 520i Sport Line', 'Bơm nước làm mát Continental cho Mercedes GLC 200 Exclusive', 'Bơm nước làm mát Graf cho Audi A4 40 TFSI Premium',
            'Két nước làm mát Denso cho Honda Civic RS 1.5L Turbo', 'Két nước làm mát Nissens cho Kia Sportage Signature AWD', 'Két nước làm mát Valeo cho Peugeot 3008 GT Line', 'Két nước làm mát NRF cho Volkswagen Tiguan Elegance', 'Két nước làm mát Hella cho Mercedes C-Class C300 AMG',
            'Két điều hòa Koyorad cho Toyota RAV4 2.5L Hybrid', 'Két điều hòa Denso cho Mitsubishi Outlander 2.4 MIVEC', 'Két điều hòa Nissens cho Ford Territory Titanium 1.5L', 'Két điều hòa Valeo cho Kia Carnival 2.2L Signature', 'Két điều hòa Delphi cho Hyundai Palisade 3.8L V6',
            'Lọc điều hòa Bosch Carbon Active cho Mazda CX-5 2.5L Premium', 'Lọc điều hòa Mann-Filter cho Mercedes GLA 200 AMG Line', 'Lọc điều hòa Denso cho Toyota Corolla Cross 1.8 HV', 'Lọc điều hòa Mahle cho BMW X1 sDrive18i xLine', 'Lọc điều hòa Sakura cho Honda HRV 1.5L RS Turbo',
            'Quạt két nước Valeo cho Peugeot 2008 1.2L PureTech GT Line', 'Quạt két nước SPAL cho Ford Bronco Sport 2.0L EcoBoost', 'Quạt két nước Nissens cho Volkswagen T-Cross 1.0 TSI', 'Quạt két nước NRF cho Audi Q3 Sportback 40 TFSI', 'Quạt két nước Denso cho Lexus IS 300 Luxury',
            'Ốc bánh xe BBS cho BMW M3 Competition xDrive', 'Ốc bánh xe Rays cho Toyota GR Yaris Circuit Pack', 'Ốc bánh xe Muteki cho Honda Civic Type R FL5', 'Ốc bánh xe Gorilla cho Ford Mustang Mach 1', 'Ốc bánh xe McGard cho Audi RS6 Avant Performance',
            'Mâm độ Vossen HF-5 cho Mercedes AMG GT 53 4-Door Coupe', 'Mâm độ Advan Racing GT cho Nissan GTR R35 Nismo', 'Mâm độ BBS FI-R cho BMW M4 Competition G82', 'Mâm độ Work Emotion ZR10 cho Subaru WRX STI S209', 'Mâm độ Rays Gram Lights 57DR cho Honda Civic Type R',
            'Lốp Michelin Pilot Sport 4 SUV 265/50R20 cho Mercedes GLE Coupe', 'Lốp Bridgestone Alenza A/S 02 275/45R21 cho Audi Q8', 'Lốp Continental ExtremeContact DWS06 Plus 245/40ZR19 cho BMW M340i', 'Lốp Pirelli P Zero PZ4 295/30ZR20 cho Porsche 911 Carrera', 'Lốp Goodyear Eagle F1 Asymmetric 6 235/45R18 cho Lexus ES 250',
            'Bộ chia nguồn âm thanh Audison Bit One HD Virtuoso cho Mercedes S-Class', 'Bộ chia nguồn âm thanh Helix DSP Ultra cho BMW 7 Series', 'Bộ chia nguồn âm thanh Mosconi 8to12 Aerospace cho Audi A8', 'Bộ chia nguồn âm thanh Arc Audio PS8-Pro cho Lexus LS 500h', 'Bộ chia nguồn âm thanh Zapco ADSP-Z8 IV-8 cho Range Rover Autobiography',
            'Loa Mid-range Focal Utopia M 3.5WM cho Mercedes S-Class Maybach', 'Loa Mid-range Dynaudio Esotar2 430 cho BMW 7 Series Individual', 'Loa Mid-range Morel Supremo 602 cho Audi A8 L', 'Loa Mid-range Scan-Speak Illuminator 12MU cho Lexus LX 600', 'Loa Mid-range Accuton C168-6-RW cho Range Rover SV Autobiography',
            'Sub điện Focal Utopia M Subwoofer 6WM cho Mercedes S-Class', 'Sub điện JL Audio 12W7AE-3 cho Cadillac Escalade ESV', 'Sub điện Alpine R-W12D2 cho GMC Yukon Denali', 'Sub điện Rockford Fosgate T1D212 cho Ford F-150 Raptor', 'Sub điện Kicker Q-Class L7 12-inch cho Chevrolet Silverado High Country',
            'Bọc vô lăng MOMO Quark Performance cho BMW M3 Competition', 'Bọc vô lăng Sparco R375 cho Ford Focus RS', 'Bọc vô lăng Nardi Deep Corn cho Mazda MX-5 Miata', 'Bọc vô lăng OMP Trecento cho Subaru BRZ tS', 'Bọc vô lăng Vertex 330mm cho Toyota GR86',
            'Gương chiếu hậu carbon bên trái cho Audi R8 Performance', 'Gương chiếu hậu carbon bên phải cho BMW M4 Competition', 'Gương chiếu hậu M Performance cho BMW M2 CS', 'Gương chiếu hậu AMG Carbon cho Mercedes AMG GT Black Series', 'Gương chiếu hậu Mugen Power cho Honda Civic Type R'
        ];
        $descriptions = [
            "Phụ tùng chính hãng được sản xuất trên dây chuyền công nghệ hiện đại, đáp ứng tiêu chuẩn chất lượng nghiêm ngặt của nhà sản xuất ô tô. Vật liệu cao cấp chống ăn mòn, chịu nhiệt tốt từ -40°C đến 150°C, đảm bảo độ bền vượt trội trong mọi điều kiện thời tiết khắc nghiệt. Thiết kế tối ưu giúp lắp đặt dễ dàng mà không cần điều chỉnh phức tạp.",
            "Sản phẩm thay thế hoàn hảo cho phụ tùng gốc với giá thành hợp lý hơn 20-30% nhưng vẫn đảm bảo hiệu suất tương đương. Được kiểm tra kỹ lưỡng qua 15 bước kiểm định chất lượng, bao gồm kiểm tra độ kín, áp lực và độ rung. Bảo hành 18 tháng cho mọi lỗi kỹ thuật xuất phát từ nhà sản xuất.",
            "Công nghệ xử lý bề mặt đặc biệt giúp tăng độ bền gấp 3 lần so với sản phẩm thông thường. Lớp phủ nano chống dính bụi bẩn và cặn dầu, duy trì hiệu suất ổn định sau 50.000 km sử dụng. Phù hợp với đa dạng phiên bản động cơ từ 2015-2023.",
            "Thiết kế thông minh giảm 30% ma sát so với thế hệ cũ, giúp tiết kiệm 5-7% nhiên liệu. Vật liệu hợp kim nhôm siêu nhẹ kết hợp lõi thép tôi nhiệt có độ cứng vượt trội. Đã được thử nghiệm độ bền qua 500.000 chu kỳ hoạt động liên tục.",
            "Giải pháp tối ưu cho các dòng xe đời mới với hệ thống điều khiển điện tử phức tạp. Tích hợp cảm biến thông minh giúp phát hiện sớm các vấn đề hư hỏng. Tương thích với đa dạng hệ thống chẩn đoán OBD-II của các hãng xe khác nhau.",
            "Ứng dụng công nghệ làm mát tiên tiến với các rãnh dẫn nhiệt độc đáo, giúp giảm nhiệt độ vận hành xuống 15-20°C. Vật liệu composite chịu nhiệt lên đến 300°C không bị biến dạng. Thiết kế module dễ dàng thay thế mà không cần tháo dỡ nhiều chi tiết.",
            "Hệ thống làm kín đa lớp với 3 vòng đệm cao su Nitrile chuyên dụng, ngăn chặn 100% rò rỉ chất lỏng. Lõi kim loại được gia công CNC chính xác đến 0.001mm. Phù hợp cho cả động cơ xăng và diesel công suất cao.",
            "Sản phẩm được phát triển bởi đội ngũ kỹ sư 20 năm kinh nghiệm, thử nghiệm thực tế trên 50 mẫu xe khác nhau. Động cơ giảm xóc thủy lực giúp vận hành êm ái hơn 40% so với phiên bản tiêu chuẩn. Bảo hành 2 năm không giới hạn km.",
            "Công nghệ phủ ceramic cao cấp giúp bề mặt chống mài mòn gấp 5 lần, chịu được áp lực làm việc lên đến 5000 PSI. Thiết kế tản nhiệt dạng tổ ong giúp tăng diện tích làm mát lên 70%. Phù hợp cho xe thường xuyên hoạt động trong điều kiện tải nặng.",
            "Hệ thống lọc đa tầng với 5 lớp vật liệu lọc đặc biệt, hiệu suất lọc đạt 99.9% các hạt bụi >5 micron. Khung nhựa ABS chống va đập, chịu nhiệt từ -30°C đến 120°C. Dễ dàng tháo lắp vệ sinh mà không cần dụng cụ chuyên dụng.",
            "Bộ phận được gia công từ thép hợp kim Cr-Mo nhiệt luyện, độ cứng đạt 55-60 HRC, chịu tải trọng động lên đến 1500kg. Bề mặt mạ kẽm điện phân chống gỉ sét trong môi trường ẩm ướt. Thiết kế góc nghiêng tối ưu giảm tiếng ồn khi vận hành.",
            "Công nghệ bôi trơn suốt đời với khoang chứa dầu tích hợp, không cần bảo dưỡng định kỳ. Vòng bi ceramic chịu được tốc độ quay lên đến 15,000 vòng/phút. Phù hợp cho hệ thống truyền động của các dòng xe hiệu suất cao.",
            "Hệ thống cách điện 3 lớp đạt tiêu chuẩn an toàn điện IP67, chống cháy nổ trong môi trường nhiên liệu. Dây dẫn đồng nguyên chất 99.99% giảm tổn hao năng lượng. Đầu nối mạ vàng chống oxy hóa, tuổi thọ lên đến 10 năm.",
            "Bộ lọc tích hợp cảm biến thông minh cảnh báo khi cần thay thế, hiển thị trên bảng đồng hồ xe. Vật liệu giấy lọc cellulose đặc biệt có thể giữ lại các hạt bụi siêu nhỏ 0.1 micron. Khung nhựa PP chịu nhiệt có thể tái chế 100% thân thiện môi trường.",
            "Thiết kế khí động học giảm lực cản không khí, tối ưu hóa hiệu suất nhiên liệu. Bề mặt phủ nano hydrophobic đẩy nước tự động, duy trì tầm nhìn rõ ràng trong điều kiện mưa lớn. Chất liệu cao su tổng hợp Silicone chịu lão hóa dưới tia UV.",
            "Hệ thống tản nhiệt dạng xoắn ốc tăng diện tích tiếp xúc lên 200% so với thiết kế thông thường. Ống dẫn bằng đồng mạ bạc dẫn nhiệt hiệu quả, kết hợp cánh tản nhiệt nhôm ép đùn. Phù hợp cho động cơ tăng áp công suất lớn.",
            "Bộ phận được sản xuất theo quy trình đúc áp lực chân không cho độ chính xác tuyệt đối. Vật liệu nhôm hợp kim A380 siêu nhẹ nhưng có độ bền tương đương thép. Xử lý nhiệt T6 giúp cải thiện độ cứng bề mặt lên 80%.",
            "Công nghệ giảm chấn thủy lực tích hợp giúp hấp thụ 90% rung động từ động cơ. Lò xo đa tầng điều chỉnh được độ cứng theo tải trọng. Phù hợp cho hệ thống treo của xe tải hạng trung và hạng nặng.",
            "Hệ thống dẫn động được tối ưu hóa với tỷ số truyền biến thiên liên tục CVT. Vật liệu composite sợi carbon gia cường giảm trọng lượng 40% so với kim loại truyền thống. Bề mặt răng được xử lý tôi cao tần đảm bảo độ bền mài mòn.",
            "Bộ lọc tích hợp van một chiều ngăn dầu chảy ngược khi tắt máy. Màng lọc sợi thủy tinh chịu nhiệt có thể lọc các hạt kim loại siêu nhỏ. Vỏ bọc thép không gỉ 304 chống ăn mòn trong môi trường muối biển.",
            "Thiết kế đa điểm tiếp xúc giúp phân bổ đều áp lực, giảm 30% mài mòn cục bộ. Chất liệu đồng thanh chống ma sát không cần bôi trơn thường xuyên. Phù hợp cho hệ thống phanh đĩa công suất cao của xe thể thao.",
            "Công nghệ phun nhiên liệu áp suất cao với độ chính xác ±0.01ms thời gian phun. Đầu phun bằng gốm chịu nhiệt độ lên đến 1500°C. Tích hợp hệ thống tự làm sạch đầu phun sau mỗi chu kỳ vận hành.",
            "Hệ thống dẫn hướng chính xác với cảm biến Hall effect cho độ chính xác vị trí 0.1°. Động cơ bước không chổi than hoạt động êm ái, tuổi thọ lên đến 1 triệu chu kỳ. Vỏ bọc IP68 chống bụi và nước hoàn toàn.",
            "Bộ trao đổi nhiệt dạng tấm với 128 kênh dẫn lưu chất song song. Vật liệu hợp kim nhôm đồng có hệ số dẫn nhiệt 200W/mK. Thiết kế ghép module cho phép mở rộng công suất làm mát khi cần thiết.",
            "Công nghệ hấp thụ sóng hài giảm ồn động cơ đến 15dB. Vật liệu composite nhiều lớp cách âm, cách nhiệt hiệu quả. Dễ dàng lắp đặt mà không cần thay đổi cấu trúc khoang máy hiện có.",
            "Hệ thống cân bằng động tự động bù trừ sai lệch trong vòng 0.2 giây. Cảm biến MEMS 3 trục đo đạc chính xác gia tốc và rung động. Phù hợp cho hệ thống truyền lực của xe all-wheel drive.",
            "Bộ lọc tích hợp bẫy dầu tách 95% dầu bôi trơn khỏi khí nén. Vật liệu lọc sợi thủy tinh chịu nhiệt kết hợp lớp than hoạt tính khử mùi. Có thể vệ sinh bằng khí nén mà không làm giảm hiệu suất lọc.",
            "Thiết kế tản nhiệt dạng cánh đồng nhất với 72 lá tản nhiệt/cm². Ống dẫn heatpipe chứa chất lỏng làm mát nhiệt độ sôi thấp. Phù hợp cho hệ thống làm mát động cơ hiệu suất cao và xe điện.",
            "Công nghệ bôi trơn cưỡng bức với bơm dầu áp suất cao 5 bar. Hệ thống lọc dầu ly tâm loại bỏ 99% tạp chất kim loại. Bình chứa dầu tích hợp làm mát bằng nước giữ nhiệt độ dầu ổn định.",
            "Hệ thống giảm xóc khí nén điều chỉnh độ cứng tự động theo tải trọng. Cảm biến hành trình độ phân giải 0.1mm. Bơm khí tích hợp có thể nâng/hạ gầm xe trong 3 giây.",
            "Bộ phận được gia công từ titan hợp kim Grade 5 chịu lực kéo đứt 1000MPa. Xử lý bề mặt anodized cứng tạo độ bền mài mòn gấp 8 lần thép. Trọng lượng nhẹ hơn 45% so với giải pháp kim loại truyền thống.",
            "Công nghệ phun phủ thermal barrier ceramic chịu nhiệt đến 1400°C. Lớp cách nhiệt dày 0.5mm giảm nhiệt độ bề mặt xuống 300°C. Phù hợp bảo vệ các chi tiết quan trọng trong buồng đốt động cơ.",
            "Hệ thống dẫn động bằng xích cam với độ chính xác góc quay ±0.25°. Xích thép hợp kim mạ đồng chống mài mòn, tuổi thọ 240.000km. Bộ căng xích tự động không cần điều chỉnh định kỳ.",
            "Bộ lọc tích hợp bẫy nước tự động tách 98% độ ẩm khỏi nhiên liệu. Màng lọc sợi thủy tinh chịu áp lực 10 bar không bị rách. Có thể vệ sinh bằng cách xả van đáy mà không cần tháo lắp.",
            "Thiết kế cánh quạt khí động học giảm tiếng ồn xuống 20dB so với thiết kế thông thường. Cánh làm bằng nhựa PPS chịu nhiệt 220°C không biến dạng. Động cơ DC không chổi than hoạt động êm với tuổi thọ 50.000 giờ.",
            "Công nghệ hàn ma sát khuấy FSW tạo mối hàn không rỗ khí, độ bền bằng 95% vật liệu gốc. Không biến dạng nhiệt, duy trì độ chính xác hình học sau khi hàn. Phù hợp cho các chi tiết chịu tải trọng động cao.",
            "Hệ thống phun xả tích cực làm sạch bụi bẩn bám trên bề mặt lọc. Khí nén áp suất 8 bar đảm bảo làm sạch 100% diện tích lọc. Chu kỳ làm sạch tự động hoặc điều khiển bằng tùy chọn.",
            "Bộ truyền động bánh răng hành tinh với hiệu suất truyền lực 98%. Bánh răng thép hợp kim 20CrMnTi nhiệt luyện chân không. Hệ thống bôi trơn cưỡng bức đảm bảo màng dầu luôn hiện diện tại điểm tiếp xúc.",
            "Công nghệ phủ DLC (Diamond-Like Carbon) độ cứng 3500HV chống mài mòn cực tốt. Hệ số ma sát chỉ 0.1 giảm tổn thất năng lượng. Phù hợp cho các chi tiết chịu ma sát liên tục như cam, cò mổ.",
            "Hệ thống treo khí nén tích hợp cảm biến tải trọng tự động điều chỉnh độ cứng. Bơm khí áp suất cao 12 bar hoạt động êm, nạp khí nhanh trong 90 giây. Điều khiển điện tử cho phép tùy chỉnh 5 chế độ lái.",
            "Bộ lọc sử dụng công nghệ lọc cyclon tách 99.5% hạt bụi >1 micron. Không sử dụng vật liệu lọc thay thế, có thể rửa sạch bằng nước. Thiết kế dòng chảy xoáy tăng hiệu suất lọc mà không gây tụt áp.",
            "Thiết kế tối ưu hóa dòng chảy giảm 40% tổn thất áp suất so với thiết kế thông thường. Bề mặt nhẵn bóng Ra 0.8μm hạn chế bám cặn. Vật liệu thép không gỉ 316L chống ăn mòn trong môi trường axit.",
            "Công nghệ cảm biến áp suất MEMS chính xác ±0.1% toàn thang đo. Tín hiệu đầu ra digital 16-bit kết nối CAN bus. Bù nhiệt tự động cho kết quả chính xác trong dải -40°C đến 125°C.",
            "Hệ thống làm mát bằng chất lỏng dielectric không dẫn điện. Bơm tuần hoàn lưu lượng 15 lít/phút, hoạt động êm <30dB. Bộ tản nhiệt nhôm ép đùn với quạt điều khiển PWM tự động điều chỉnh tốc độ.",
            "Bộ phận được đúc áp lực với công nghệ khuôn mẫu chính xác ±0.05mm. Vật liệu nhôm hợp kim A356-T6 có độ bền kéo 310MPa. Xử lý bề mặt shot peening tăng 30% độ bền mỏi.",
            "Công nghệ phun nhiên liệu áp suất siêu cao 3500 bar cho quá trình đốt cháy hoàn toàn. Đầu phun bằng gốm zirconia chịu nhiệt 1600°C. Thời gian đáp ứng nhanh 0.1ms cho hiệu suất động cơ tối ưu.",
            "Hệ thống truyền động bằng đai timing với độ chính xác vị trí tuyệt đối. Đai làm bằng vật liệu polyurethane bền dầu, chịu nhiệt 120°C. Bộ căng đai tự động duy trì lực căng tối ưu suốt vòng đời sử dụng.",
            "Bộ lọc tích hợp lõi lọc từ tính hút các hạt kim loại siêu nhỏ. Hai lớp lọc thô và tinh cho hiệu suất lọc 99.97% ở 0.3 micron. Vỏ composite chịu va đập, chống rung động mạnh.",
            "Thiết kế tản nhiệt dạng pin với 120 lá tản nhiệt/cm² tăng diện tích trao đổi nhiệt. Ống heatpipe chứa chất lỏng làm mát nhiệt độ sôi thấp. Quạt PWM điều chỉnh tốc độ theo nhiệt độ động cơ.",
            "Công nghệ phủ PVD TiN tạo bề mặt cứng 2500HV, hệ số ma sát chỉ 0.15. Chống dính bám hiệu quả, giảm mài mòn trong điều kiện khô. Phù hợp cho các chi tiết chịu ma sát khô như piston, xilanh.",
            "Hệ thống giảm chấn thủy lực tích hợp cảm biến gia tốc 3 trục. Tự động điều chỉnh độ giảm chấn theo điều kiện đường và tốc độ. Có thể nâng cấp firmware để tối ưu cho từng dòng xe cụ thể.",
            "Bộ truyền động bánh răng côn xoắn với độ chính xác cấp 5 DIN. Bề mặt răng được mài bóng Ra 0.4μm giảm ồn khi vận hành. Hệ thống bôi trơn phun sương đảm bảo dầu đến mọi điểm tiếp xúc.",
            "Công nghệ composite sợi carbon gia cường epoxy cho tỷ lệ độ bền/trọng lượng vượt trội. Chịu nhiệt liên tục 200°C, đỉnh 300°C trong thời gian ngắn. Không bị ăn mòn trong môi trường muối, axit nhẹ.",
            "Hệ thống phun urea SCR với bơm áp suất cao 10 bar, độ chính xác ±1%. Đầu phun bằng gốm chịu nhiệt 1300°C, tự làm sạch sau mỗi chu kỳ. Tích hợp bộ gia nhiệt chống đông cho vùng khí hậu lạnh.",
            "Bộ lọc sử dụng công nghệ lọc hạt tĩnh điện thu giữ 99.9% hạt PM2.5. Có thể rửa sạch và tái sử dụng nhiều lần. Tích hợp cảm biến chênh áp cảnh báo khi cần vệ sinh.",
            "Thiết kế tối ưu dòng khí giảm 30% tổn thất áp suất so với thiết kế thông thường. Cánh dẫn hướng bằng hợp kim nhôm siêu nhẹ, bề mặt phủ ceramic. Phù hợp cho hệ thống nạp khí động cơ tăng áp.",
            "Công nghệ hàn laser fiber cho mối hàn sâu, hẹp, ít biến dạng nhiệt. Độ chính xác vị trí hàn ±0.02mm. Không cần vật liệu hàn phụ, mối hàn sạch không xỉ.",
            "Hệ thống bôi trơn phun sương áp suất cao 8 bar đảm bảo dầu đến mọi ngóc ngách. Bơm dầu bánh răng thủy lực lưu lượng 20 lít/phút. Tích hợp bộ làm mát dầu giữ nhiệt độ ổn định 80-90°C.",
            "Bộ phận được gia công 5 trục CNC đạt độ chính xác ±0.01mm. Vật liệu Inconel 718 chịu nhiệt 700°C liên tục. Xử lý bề mặt shot peening tăng 40% độ bền mỏi so với phương pháp thông thường.",
            "Công nghệ phun phủ thermal barrier YSZ dày 300μm chịu nhiệt 1200°C. Giảm nhiệt độ bề mặt chi tiết xuống 200-300°C. Phù hợp cách nhiệt cho turbine khí và các chi tiết buồng đốt.",
            "Hệ thống truyền động xích con lăn kép chịu tải trọng động cao. Xích mạ Niken chống mài mòn, tuổi thọ 5000 giờ hoạt động liên tục. Bộ căng xích tự động không cần bảo dưỡng định kỳ.",
            "Bộ lọc tích hợp bẫy dầu ly tâm tách 99% dầu khỏi khí nén. Hai cấp lọc thô và tinh với vật liệu lọc composite đặc biệt. Có thể vệ sinh bằng khí nén mà không làm giảm hiệu suất lọc.",
            "Thiết kế cánh quạt khí động học giảm tiếng ồn xuống 25dB. Cánh làm bằng nhựa PEEK chịu nhiệt 250°C, chống hóa chất. Động cơ EC không chổi than hiệu suất 90%, tuổi thọ 60.000 giờ.",
            "Công nghệ đúc áp lực chân không cho sản phẩm không rỗ khí, độ đặc chắc 100%. Vật liệu nhôm hợp kim A380 có độ bền kéo 320MPa. Xử lý nhiệt T6 đạt độ cứng 95HB, chống mài mòn tốt.",
            "Hệ thống giảm xóc magnetorheological với thời gian đáp ứng 5ms. Chất lỏng thông minh thay đổi độ nhớt theo từ trường. 10 cấp độ điều chỉnh từ êm dịu đến thể thao qua hệ thống điều khiển."
        ];
        $files = collect(Storage::disk('public')->allFiles($this->path))
            ->filter(function ($file) {
                return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
            })->values()->all();
        
        foreach ($products as $productName) {
            $product = Product::create([
                'name' => $productName . ' ' . $this->faker->bothify('##??'),
                'description' => $this->faker->randomElement($descriptions),
                'original_price' => $this->faker->numberBetween(100000, 5000000),
                'quantity' => $this->faker->numberBetween(0, 1000),
                'sold' => $this->faker->numberBetween(0, 200),
                'dimensions' => $this->faker->randomElement([
                    'Kích thước nhỏ gọn: 10 x 5 x 5 cm', 'Dài 20cm, rộng 15cm, cao 10cm – vừa vặn vị trí lắp đặt', 'Thiết kế tiêu chuẩn: 30 x 20 x 10 cm', 'Kích thước tổng thể: 40 x 25 x 15 cm – phù hợp nhiều dòng xe', 'Dài 30cm – Rộng 20cm – Cao 10cm, dễ lắp đặt'
                ]),
                'weight' => $this->faker->randomElement([
                    'Chỉ nặng 0.2kg – nhẹ, dễ vận chuyển', 'Trọng lượng: 0.5kg – phù hợp tiêu chuẩn kỹ thuật', 'Nặng 1kg – chắc chắn, bền bỉ', '2.5kg – đảm bảo độ ổn định khi sử dụng', 'Khối lượng 5kg – thích hợp với linh kiện cỡ lớn'
                ]),
                'color' => $this->faker->randomElement([
                    'Màu đen sang trọng, chống bám bẩn', 'Bạc ánh kim – nổi bật và tinh tế', 'Xám mờ – phù hợp phong cách mạnh mẽ', 'Trắng tinh khiết – dễ phối với nhiều màu nội thất', 'Đỏ thể thao – nổi bật và cá tính', 'Xanh dương hiện đại – tăng tính thẩm mỹ cho xe'
                ]),
                'material' => $this->faker->randomElement([
                    'Chất liệu nhôm cao cấp – nhẹ và bền', 'Thép không gỉ – chống ăn mòn, dùng lâu dài', 'Cao su tổng hợp – độ đàn hồi tốt, chịu nhiệt', 'Nhựa ABS chống va đập – đảm bảo an toàn', 'Sắt hợp kim – chịu lực tốt, không bị biến dạng', 'Hợp kim cao cấp – độ bền vượt trội'
                ]),
                'compatibility' => $this->faker->randomElement([
                    'Phù hợp với các dòng Toyota phổ biến', 'Dành riêng cho xe Honda đời mới', 'Lắp đặt chuẩn cho Ford Ranger, EcoSport...', 'Tương thích với Hyundai Accent, Elantra...',
                    'Dùng tốt cho Mazda 3, CX-5, BT-50...', 'Phù hợp Kia Morning, Sorento, Cerato...', 'Sử dụng cho Nissan Navara, X-Trail...', 'Tương thích với Suzuki Swift, Ciaz, XL7...'
                ]),
                'promotion_id' => $this->faker->optional()->randomElement($this->promotionIds),
                'unit_id' => $this->faker->randomElement($this->unitIds),
                'supplier_id' => $this->faker->randomElement($this->supplierIds),
            ]);
            $product->categories()->attach($this->faker->randomElements($this->categoryIds, min(5, count($this->categoryIds))));
            Image::create([
                'product_id' => $product->id,
                'filename' => 0,
                'path' => '/storage/' . $this->faker->randomElement($files),
                'is_thumbnail' => true,
                'size' => rand(100000, 800000),
                'mime_type' => 'image/jpeg',
            ]);
            $images = [];
            for ($j = 1; $j <= 9; $j++) {
                $images[] = [
                    'product_id' => $product->id,
                    'filename' => $j,
                    'path' => '/storage/' . $this->faker->randomElement($files),
                    'is_thumbnail' => false,
                    'size' => rand(100000, 800000),
                    'mime_type' => 'image/jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Image::insert($images);
        }
    }
}
