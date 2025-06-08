<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Image;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    private $faker;
    private $usersId;
    private $categoryIds = [];
    private $path = 'sanhua/tin-tuc';
    private $titles = [
        'Sanhua Intelligent Controls đạt Top 10 Doanh nghiệp Công nghệ Xanh Châu Á 2025', 'Ký kết hợp tác chiến lược với Samsung Electronics trong lĩnh vực IoT', 'Sanhua khánh thành nhà máy thông minh trị giá 500 triệu USD tại Thượng Hải', 'Ra mắt dòng sản phẩm điều khiển nhiệt độ thế hệ mới tại AWE 2025', 'Sanhua nhận giải "Doanh nghiệp Đổi mới Xuất sắc" từ Chính phủ Trung Quốc', 'Hợp tác với Bosch phát triển hệ thống làm lạnh cho xe điện', 'Tổ chức thành công Hội nghị Khách hàng Toàn cầu tại Hàng Châu', 'Sanhua công bố kế hoạch đầu tư 1 tỷ USD vào R&D trong 5 năm tới', 'Đạt chứng nhận ISO 14001 cho hệ thống quản lý môi trường', 'Khai trương Trung tâm Đào tạo Công nghệ Cao tại Chiết Giang',
        'Sanhua mua lại công ty công nghệ Đức, mở rộng thị trường châu Âu', 'Sanhua ký thỏa thuận hợp tác với LG Electronics phát triển công nghệ HVAC thông minh', 'Ra mắt hệ thống làm lạnh siêu tốc cho ngành thực phẩm tại Food Tech Asia 2025', 'Sanhua đạt chứng nhận Carbon Neutral cho 5 nhà máy sản xuất chính', 'Khai trương Viện Nghiên cứu Năng lượng Tái tạo tại Singapore', 'Hợp tác với Microsoft phát triển nền tảng IoT quản lý năng lượng tòa nhà', 'Sanhua nhận đầu tư 200 triệu USD từ quỹ Green Technology Fund', 'Tổ chức thành công Hội thảo Quốc tế về Công nghệ Làm lạnh Xanh', 'Ra mắt ứng dụng di động Smart Climate Control cho người tiêu dùng', 'Sanhua ký hợp đồng cung cấp hệ thống HVAC cho dự án thành phố thông minh Dubai',
        'Đạt kỷ lục xuất khẩu 5 tỷ USD năm 2024, tăng trưởng 25% so với năm trước', 'Sanhua trở thành đối tác chính thức của Formula E trong công nghệ làm mát pin', 'Khai trương Trung tâm Nghiên cứu Trí tuệ Nhân tạo tại Tokyo', 'Sanhua nhận chứng nhận LEED Platinum cho trụ sở chính mới', 'Ký hợp đồng cung cấp hệ thống HVAC cho sân bay quốc tế Changi Terminal 5', 'Ra mắt công nghệ làm lạnh bằng từ trường đầu tiên trên thế giới', 'Sanhua đầu tư 800 triệu USD xây dựng Khu công nghiệp sinh thái tại Brazil', 'Hợp tác với SpaceX phát triển hệ thống kiểm soát nhiệt độ cho tàu vũ trụ', 'Giành giải Grand Prix tại World Smart City Awards 2025', 'Sanhua công bố lộ trình Net Zero Emission đến năm 2035',
        'Ký thỏa thuận hợp tác với Siemens Energy về công nghệ hydro xanh', 'Sanhua phá kỷ lục thế giới với hệ thống làm lạnh -300°C cho nghiên cứu vật lý', 'Hợp tác với Google DeepMind phát triển AI điều khiển khí hậu thông minh', 'Khai trương nhà máy sản xuất chất làm lạnh sinh học đầu tiên tại Scandinavia', 'Sanhua được chọn là nhà cung cấp độc quyền cho Olympic mùa đông 2026', 'Sanhua đạt chứng nhận B-Corporation đầu tiên trong ngành HVAC toàn cầu', 'Ký thỏa thuận hợp tác với UN-Habitat về giải pháp khí hậu đô thị bền vững',
    ];
    private $highlights = [
        'Sanhua lọt vào danh sách Fortune 500 năm 2025', 'Ký kết thỏa thuận hợp tác với Panasonic về công nghệ HVAC', 'Sanhua đạt doanh thu kỷ lục 10 tỷ USD năm 2024', 'Giải thưởng "Nhà cung cấp xuất sắc" từ Toyota', 'Lễ khởi công dự án nhà máy tại Mexico, mở rộng thị trường Bắc Mỹ', 'Ra mắt phòng thí nghiệm AI ứng dụng trong điều khiển nhiệt độ', 'Sanhua tài trợ chương trình đào tạo kỹ sư trẻ toàn cầu', 'Được vinh danh "Doanh nghiệp Bền vững hàng đầu ngành Công nghiệp Lạnh"', 'Kỷ niệm 35 năm thành lập với chuỗi sự kiện đặc biệt', 'Sanhua hợp tác với Tesla phát triển hệ thống làm mát pin xe điện',
        'Nhận giải "Thương hiệu Quốc tế Xuất sắc" tại Triển lãm Hannover Messe', 'Sanhua được bình chọn "Thương hiệu Tin cậy nhất" trong ngành HVAC châu Á', 'Ký kết hợp tác với General Electric về công nghệ turbin gió offshore', 'Sanhua mở rộng sản xuất tại Việt Nam với nhà máy trị giá 300 triệu USD', 'Giành giải "Sản phẩm Sáng tạo Xuất sắc" tại CES 2025 với hệ thống AI HVAC', 'Lễ động thổ Khu công nghệ cao Sanhua tại Bangalore, Ấn Độ', 'Ra mắt chương trình học bổng "Sanhua Green Future" cho sinh viên toàn cầu', 'Được vinh danh "Nhà tuyển dụng hàng đầu" trong ngành công nghệ xanh', 'Kỷ niệm cột mốc 1 tỷ sản phẩm được bán ra trên toàn thế giới', 'Sanhua hợp tác với NASA phát triển hệ thống làm lạnh cho trạm vũ trụ',
        'Nhận chứng nhận "Zero Waste Manufacturing" cho tất cả cơ sở sản xuất', 'Sanhua được vinh danh "Công ty Công nghệ Xanh của Thập kỷ" tại Global Tech Awards', 'Ký kết hợp tác với BMW Group phát triển hệ thống nhiệt động lực học xe tương lai', 'Ra mắt chuỗi cửa hàng trải nghiệm công nghệ thông minh tại 50 thành phố lớn', 'Sanhua đạt kỷ lục 15 tỷ USD doanh thu quý IV/2024', 'Nhận đầu tư chiến lược 1.5 tỷ USD từ SoftBank Vision Fund', 'Khai trương Viện Công nghệ Vật liệu Tiên tiến tại Munich', 'Sanhua trở thành nhà tài trợ chính của Olympic Paris 2024', 'Giành 12 giải thưởng sáng tạo tại triển lãm CES 2025', 'Ký hợp đồng độc quyền cung cấp công nghệ cho dự án thành phố NEOM',
        'Sanhua được bình chọn "Thương hiệu Đáng tin cậy nhất thế giới" 3 năm liên tiếp'
    ];
    private $section = [
        "Công nghệ điều khiển nhiệt độ thông minh ứng dụng AI và machine learning", "Giải pháp HVAC tích hợp IoT cho tòa nhà thương mại hiện đại", "Chiến lược hợp tác toàn cầu với các đối tác công nghệ hàng đầu", "Đột phá trong nghiên cứu vật liệu làm lạnh thân thiện môi trường", "Lộ trình phát triển bền vững và giảm carbon đến năm 2030", "Hệ thống quản lý chất lượng đạt chuẩn ISO 9001:2015 và ASME", "Ứng dụng công nghệ số trong giám sát hệ thống điều hòa trung tâm", "Giải pháp tối ưu năng lượng cho hệ thống làm lạnh công nghiệp", "Công nghệ bơm nhiệt inverter tiết kiệm điện năng vượt trội", "Hệ thống kiểm soát vi khí hậu cho phòng sạch công nghiệp",
        "Phát triển sản phẩm theo tiêu chuẩn LEED và WELL Building", "Tự động hóa toàn bộ dây chuyền sản xuất van điều tiết", "Nghiên cứu hợp chất làm lạnh thế hệ mới không gây hiệu ứng nhà kính", "Giải pháp tích hợp cho hệ thống kho lạnh logistics chuỗi cung ứng", "Hệ thống điều hòa VRV/VRF công suất lớn cho cao ốc văn phòng", "Công nghệ điều khiển độ ẩm chính xác cho ngành dược phẩm", "Giải pháp làm mát trung tâm dữ liệu đạt tiêu chuẩn Tier IV", "Phát triển hệ thống nhiệt điện cho xe điện thế hệ mới", "Công nghệ khử trùng không khí bằng tia UV-C và ion hóa", "Hệ thống EMS quản lý năng lượng thông minh đa cấp độ",
        "Giải pháp kiểm soát nhiệt độ cho nhà máy sản xuất vi mạch", "Công nghệ van tiết lưu điện tử chính xác cao EEV", "Hệ thống điều khiển từ xa tích hợp trí tuệ nhân tạo AI", "Giải pháp làm lạnh công nghiệp nhiệt độ cực thấp (-80°C)", "Công nghệ giám sát thiết bị từ xa và bảo trì dự đoán", "Hệ thống trao đổi nhiệt hiệu suất cao sử dụng vật liệu nano", "Giải pháp nhiệt lạnh tổng thể cho khu đô thị thông minh", "Công nghệ thu hồi nhiệt thải để tái sử dụng năng lượng", "Hệ thống điều áp và thông gió cho tầng hầm cao ốc", "Phát triển thiết bị đo lường và kiểm soát lưu lượng chính xác",
        "Công nghệ chống ăn mòn và bảo vệ bề mặt thiết bị làm lạnh", "Giải pháp cân bằng tải cho hệ thống điều hòa trung tâm", "Hệ thống xử lý khí thải và tái chế nhiệt năng", "Công nghệ giảm tiếng ồn và rung động trong hệ thống HVAC", "Phát triển vật liệu cách nhiệt hiệu suất cao từ sợi carbon", "Giải pháp tích hợp năng lượng mặt trời vào hệ thống làm lạnh", "Công nghệ blockchain trong quản lý chuỗi cung ứng linh kiện HVAC", "Hệ thống điều hòa năng lượng mặt trời tích hợp pin lưu trữ", "Giải pháp làm lạnh không khí nén cho ngành công nghiệp nặng", "Công nghệ cảm biến thông minh đo chất lượng không khí real-time",
        "Hệ thống kiểm soát áp suất động cho đường ống dẫn khí công nghiệp", "Phát triển vật liệu siêu dẫn nhiệt từ graphene và carbon nanotube", "Giải pháp tự động hóa nhà thông minh tích hợp voice control", "Công nghệ thu hồi nước từ không khí cho khu vực khan hiếm nước", "Hệ thống làm lạnh từ tính không tiếng ồn cho bệnh viện", "Giải pháp điều hòa không khí sạch tích hợp UV-C và HEPA filter", "Công nghệ làm lạnh lượng tử ứng dụng trong siêu máy tính", "Hệ thống kiểm soát vi khí hậu cho nông nghiệp thông minh vertical farming", "Giải pháp thu hồi và tái chế 100% chất làm lạnh công nghiệp", "Công nghệ cảm biến sinh học giám sát sức khỏe qua chất lượng không khí",
        "Hệ thống điều hòa tự cung cấp năng lượng bằng pin nhiên liệu hydro", "Giải pháp làm mát sinh học sử dụng enzyme tự nhiên", "Công nghệ hologram 3D cho giao diện điều khiển HVAC thế hệ mới", "Hệ thống purify không khí bằng công nghệ plasma lạnh", "Giải pháp điều hòa không trọng lực cho trạm vũ trụ thương mại", "Công nghệ metamaterial điều khiển sóng nhiệt chính xác nano"
    ];
    private $paragraphs = [
        "Sanhua đã và đang dẫn đầu trong việc ứng dụng trí tuệ nhân tạo vào hệ thống điều khiển nhiệt độ, cho phép hệ thống tự học thói quen sử dụng và tự động điều chỉnh để tối ưu hóa hiệu suất năng lượng, giảm thiểu chi phí vận hành lên đến 30% so với các hệ thống thông thường.",
        "Với việc tích hợp công nghệ IoT vào các giải pháp HVAC, Sanhua mang đến khả năng giám sát và điều khiển từ xa thông qua nền tảng điện toán đám mây, cho phép quản lý tập trung nhiều hệ thống tại các địa điểm khác nhau chỉ thông qua một giao diện duy nhất.",
        "Chiến lược hợp tác toàn cầu của Sanhua tập trung vào việc phát triển các liên minh công nghệ với các tập đoàn hàng đầu như Siemens, Mitsubishi Electric và Honeywell, nhằm tạo ra các giải pháp tích hợp đa năng với hiệu suất vượt trội.",
        "Đột phá mới nhất của Sanhua trong lĩnh vực vật liệu làm lạnh là việc phát triển thành công hợp chất mới có chỉ số GWP (Global Warming Potential) thấp hơn 75% so với các chất làm lạnh truyền thống, đồng thời vẫn đảm bảo hiệu suất làm lạnh cao.",
        "Lộ trình phát triển bền vững đến năm 2030 của Sanhua bao gồm 3 trụ cột chính: giảm 50% lượng khí thải carbon trong sản xuất, tăng tỷ lệ sử dụng năng lượng tái tạo lên 40% và phát triển 100% sản phẩm thân thiện môi trường.",
        "Hệ thống quản lý chất lượng của Sanhua không chỉ đạt các tiêu chuẩn quốc tế khắt khe nhất mà còn liên tục được cải tiến thông qua việc áp dụng các công nghệ quản lý tiên tiến như Six Sigma và TQM, đảm bảo chất lượng sản phẩm đồng nhất trên toàn cầu.",
        "Việc ứng dụng công nghệ số hóa trong giám sát hệ thống điều hòa trung tâm cho phép Sanhua cung cấp các giải pháp predictive maintenance (bảo trì dự đoán), giúp phát hiện sớm các nguy cơ hư hỏng và giảm thiểu thời gian ngừng hoạt động của hệ thống.",
        "Giải pháp tối ưu năng lượng của Sanhua cho hệ thống làm lạnh công nghiệp kết hợp nhiều công nghệ tiên tiến như biến tần thông minh, thu hồi nhiệt thải và điều khiển tối ưu theo tải, mang lại hiệu quả tiết kiệm năng lượng lên đến 45% so với giải pháp truyền thống.",
        "Công nghệ bơm nhiệt inverter của Sanhua đạt hiệu suất COP lên đến 4.5, vượt trội so với các sản phẩm cùng loại trên thị trường, nhờ vào việc ứng dụng thuật toán điều khiển thông minh và thiết kế tối ưu hóa trao đổi nhiệt.",
        "Hệ thống kiểm soát vi khí hậu của Sanhua cho phòng sạch công nghiệp đáp ứng các tiêu chuẩn khắt khe nhất như ISO 14644-1 Class 5, với khả năng duy trì nhiệt độ ổn định ±0.5°C và độ ẩm ±2% trong mọi điều kiện vận hành.",
        "Các sản phẩm của Sanhua được phát triển theo các tiêu chuẩn xây dựng xanh LEED và WELL Building, hỗ trợ các công trình đạt chứng nhận về hiệu quả năng lượng và chất lượng môi trường trong nhà, góp phần nâng cao sức khỏe người sử dụng.",
        "Dây chuyền sản xuất van điều tiết của Sanhua đã đạt mức độ tự động hóa 95%, với hệ thống robot công nghiệp thế hệ mới nhất, hệ thống kiểm tra chất lượng bằng hình ảnh AI và hệ thống đóng gói tự động, đảm bảo năng suất và chất lượng vượt trội.",
        "Nghiên cứu hợp chất làm lạnh thế hệ mới của Sanhua tập trung vào các giải pháp thân thiện môi trường với chỉ số ODP (Ozone Depletion Potential) bằng 0 và GWP dưới 150, đồng thời đáp ứng đầy đủ các quy định khắt khe của EPA và EU F-gas.",
        "Giải pháp tích hợp cho hệ thống kho lạnh logistics của Sanhua bao gồm hệ thống quản lý nhiệt độ đa vùng, hệ thống giám sát hàng tồn kho thông minh và giải pháp tiết kiệm năng lượng toàn diện, phục vụ hiệu quả cho chuỗi cung ứng thực phẩm và dược phẩm.",
        "Hệ thống điều hòa VRV/VRF của Sanhua cho cao ốc văn phòng được thiết kế đặc biệt với công suất lên đến 60HP, khả năng kết nối đến 64 indoor units và hiệu suất năng lượng đạt 5 sao theo tiêu chuẩn ENERGY STAR.",
        "Công nghệ điều khiển độ ẩm chính xác của Sanhua cho ngành dược phẩm đạt độ chính xác ±1% RH trong dải từ 30% đến 70% RH, đáp ứng các yêu cầu khắt khe của GMP và FDA trong sản xuất dược phẩm và thiết bị y tế.",
        "Giải pháp làm mát trung tâm dữ liệu của Sanhua đạt tiêu chuẩn Tier IV với khả năng hoạt động liên tục 99.995%, hệ thống làm lạnh dự phòng N+1 và giải pháp làm lạnh bằng nước tiên tiến giúp giảm PUE xuống dưới 1.3.",
        "Hệ thống nhiệt điện cho xe điện của Sanhua ứng dụng công nghệ bơm nhiệt CO2 với khả năng hoạt động hiệu quả trong điều kiện nhiệt độ thấp đến -30°C, tăng phạm vi hoạt động của xe điện lên 20% so với hệ thống truyền thống.",
        "Công nghệ blockchain được Sanhua ứng dụng để tạo ra hệ thống truy xuất nguồn gốc hoàn toàn minh bạch cho tất cả linh kiện HVAC, từ nguyên liệu thô đến sản phẩm hoàn thiện, giúp khách hàng dễ dàng xác minh chất lượng và tuân thủ các tiêu chuẩn quốc tế một cách tự động.",
        "Hệ thống điều hòa năng lượng mặt trời thế hệ mới của Sanhua tích hợp pin lithium-ion công suất lớn với khả năng lưu trữ đến 48kWh, cho phép vận hành liên tục trong 12 giờ không cần điện lưới, đặc biệt phù hợp cho các khu vực có nguồn điện không ổn định.",
        "Giải pháp làm lạnh không khí nén công nghiệp của Sanhua đạt hiệu suất làm lạnh vượt trội với khả năng giảm nhiệt độ không khí nén từ 200°C xuống 40°C chỉ trong 30 giây, đồng thời thu hồi 85% nhiệt thải để tái sử dụng cho các quy trình khác trong nhà máy.",
        "Cảm biến thông minh mới nhất của Sanhua có khả năng đo đồng thời 15 thông số chất lượng không khí bao gồm PM2.5, CO2, VOC, và các vi khuẩn có hại, với độ chính xác lên đến 99.9% và khả năng cập nhật dữ liệu real-time mỗi 5 giây.",
        "Hệ thống kiểm soát áp suất động của Sanhua sử dụng thuật toán AI tiên tiến để dự đoán và điều chỉnh áp suất trong đường ống dẫn khí công nghiệp, giảm thiểu 90% rủi ro rò rỉ và tăng 40% hiệu suất vận chuyển khí so với hệ thống thông thường.",
        "Nghiên cứu vật liệu siêu dẫn nhiệt từ graphene và carbon nanotube của Sanhua đã tạo ra vật liệu có hệ số dẫn nhiệt gấp 10 lần đồng nguyên chất, mở ra khả năng thiết kế các thiết bị trao đổi nhiệt siêu nhỏ gọn với hiệu suất vượt trội chưa từng có.",
        "Giải pháp nhà thông minh của Sanhua tích hợp voice control hỗ trợ 25 ngôn ngữ khác nhau, có khả năng nhận diện giọng nói với độ chính xác 98% trong môi trường có tiếng ồn và tự động học thói quen sinh hoạt của gia đình để tối ưu hóa tiêu thụ năng lượng.",
        "Công nghệ thu hồi nước từ không khí của Sanhua có thể tạo ra 30 lít nước uống sạch mỗi ngày từ không khí có độ ẩm tối thiểu 35%, sử dụng năng lượng mặt trời hoàn toàn và đạt tiêu chuẩn nước uống WHO, đặc biệt phù hợp cho các khu vực sa mạc và hải đảo.",
        "Hệ thống làm lạnh từ tính không tiếng ồn của Sanhua hoạt động hoàn toàn im lặng dưới 20dB, sử dụng hiệu ứng từ nhiệt để làm lạnh mà không cần máy nén hay chất làm lạnh truyền thống, đặc biệt phù hợp cho các khu vực nhạy cảm như phòng phẫu thuật và phòng ICU.",
        "Giải pháp điều hòa không khí sạch tích hợp UV-C và HEPA filter của Sanhua có khả năng tiêu diệt 99.99% vi khuẩn, virus và loại bỏ 99.97% các hạt bụi PM0.3, đồng thời tích hợp hệ thống ion hóa để tạo ra môi trường không khí trong lành tương đương với không khí núi cao.",
        "Công nghệ làm lạnh lượng tử mới nhất của Sanhua sử dụng hiệu ứng Peltier cải tiến với vật liệu siêu dẫn nhiệt độ phòng, đạt hiệu suất làm lạnh gấp 50 lần hệ thống truyền thống và có khả năng giảm nhiệt độ xuống -273°C trong vòng 10 giây, đặc biệt phù hợp cho việc làm mát các siêu máy tính lượng tử.",
        "Hệ thống vi khí hậu cho nông nghiệp thông minh của Sanhua có thể tạo ra 12 vùng khí hậu khác nhau trong cùng một không gian, mỗi vùng được điều khiển độc lập về nhiệt độ, độ ẩm, nồng độ CO2 và cường độ ánh sáng, giúp tăng năng suất cây trồng lên 300% so với phương pháp truyền thống.",
        "Giải pháp thu hồi chất làm lạnh của Sanhua đạt tỷ lệ tái chế 99.98% thông qua công nghệ chưng cất phân tử tiên tiến kết hợp với hệ thống lọc ion đa tầng, không chỉ bảo vệ môi trường mà còn giảm 70% chi phí sử dụng chất làm lạnh cho doanh nghiệp.",
        "Cảm biến sinh học thông minh của Sanhua có khả năng phát hiện 200 chỉ số sinh học qua hơi thở và mồ hôi, bao gồm glucose, cortisol, adrenaline và các biomarker ung thư, cung cấp cảnh báo sức khỏe real-time với độ chính xác 99.5% được xác nhận bởi FDA.",
        "Hệ thống điều hòa hydro của Sanhua sử dụng pin nhiên liệu PEM thế hệ thứ 5 với khả năng tự sản xuất điện và nước từ hydro, vận hành liên tục 30 ngày không cần bảo trì và chỉ thải ra hơi nước tinh khiết, đạt hiệu suất năng lượng 95%.",
        "Công nghệ làm mát sinh học độc quyền của Sanhua sử dụng enzyme thermostable được biến đổi gen có khả năng hấp thụ nhiệt trong quá trình phản ứng, tạo ra hiệu ứng làm lạnh tự nhiên mà không cần tiêu thụ điện năng, đồng thời tạo ra các sản phẩm phụ có lợi cho sức khỏe.",
        "Giao diện hologram 3D của Sanhua cho phép người dùng tương tác trực tiếp với mô hình 3D của hệ thống HVAC bằng cử chỉ tay, hỗ trợ điều khiển bằng giọng nói 50 ngôn ngữ và có khả năng hiển thị dữ liệu real-time dưới dạng thực tế ảo với độ phân giải 8K.",
        "Hệ thống purify plasma lạnh của Sanhua tạo ra plasma ở nhiệt độ phòng để phá hủy 99.999% virus, vi khuẩn và các hợp chất hữu cơ độc hại, đồng thời tái tạo ozone tự nhiên và ion âm có lợi cho sức khỏe, tiêu thụ điện năng chỉ bằng 1/10 so với hệ thống UV truyền thống.",
        "Giải pháp điều hòa không trọng lực của Sanhua sử dụng nguyên lý đối lưu cưỡng bức bằng từ trường để lưu chuyển không khí trong môi trường không trọng lực, đạt hiệu suất trao đổi nhiệt 40% cao hơn so với hệ thống quạt thông thường và hoạt động hoàn toàn im lặng.",
        "Vật liệu metamaterial của Sanhua có cấu trúc nano được thiết kế đặc biệt để điều hướng sóng nhiệt theo ý muốn, có thể tạo ra vùng ẩn nhiệt hoàn toàn hoặc tập trung nhiệt vào một điểm với độ chính xác đến từng nanometer, mở ra khả năng ứng dụng trong vi điện tử và y học chính xác."
    ];

    public function __construct()
    {
        $this->faker = Faker::create('vi_VN');
        $this->usersId = User::whereNotNull('role_id')->pluck('id')->toArray();
    }

    private function generateArticleContent(array $files): string
    {
        $content = '';
        $j = 0;
        for ($section = 0; $section < 3; $section++) {
            $content .= '<div><b>' . $this->faker->randomElement($this->section) . '</b></div>';
            $numSentences = rand(3, 5);
            for ($i = 0; $i < $numSentences; $i++) {
                $content .= '<p>' . $this->faker->randomElement($this->paragraphs) . '</p>';
            }
            $randomImages = collect($files)->shuffle()->take(rand(1, 2));
            foreach ($randomImages as $image) {
                $filename = $j;
                $path = basename($image);
                $content .= '<img alt="' . $filename . '" src="/storage/' . $path . '">';
                $j++;
            }
        }
        $content .= '<p>Với chiến lược phát triển bền vững và cam kết không ngừng đổi mới, Sanhua tiếp tục khẳng định vị thế là nhà cung cấp giải pháp nhiệt lạnh hàng đầu thế giới, mang đến những giá trị vượt trội cho khách hàng và đóng góp tích cực vào sự phát triển của ngành công nghiệp toàn cầu.</p>';
        $content .= '<p><strong>Sanhua Vietnam Co., Ltd.</strong><br>';
        $content .= 'Địa chỉ: Thửa đất B16, B17, B18, B19, B20, B21 thuộc lô đất CN6 Khu công nghiệp An Dương, Xã Hồng Phong, Huyện An Dương, Thành phố Hải Phòng, Việt Nam.<br>';
        $content .= 'Hotline: 02256259777 | Email: Hrvn@ic.sanhuagroup.com<br>';
        return $content;
    }

    private function createArticles(array $files): void {
        $categories = [
            ['name' => 'Tin nổi bật', 'type' => 'article'],
            ['name' => 'Tin công ty', 'type' => 'article'],
            ['name' => 'Tin bán hàng', 'type' => 'article'],
        ];
        
        foreach ($categories as $c) {
            $category = Category::create($c);
            $this->categoryIds[] = $category->id;
        }
        
        for ($i = 0; $i < 50; $i++) {
            $article = Article::create([
                'author' => $this->faker->randomElement($this->usersId),
                'approved_by' => $this->faker->randomElement($this->usersId),
                'title' => $this->faker->randomElement($this->titles),
                'highlight' => $this->faker->randomElement($this->highlights),
                'publish_date' => $this->faker->dateTimeBetween('-6 months', '+1 week')->format('Y-m-d'),
                'content' => $this->generateArticleContent($files),
                'category_id' => $this->faker->randomElement($this->categoryIds),
            ]);
            Image::create([
                'article_id' => $article->id,
                'filename' => 5,
                'path' => '/storage/' . $this->faker->randomElement($files),
                'is_thumbnail' => true,
                'size' => rand(100000, 800000),
                'mime_type' => 'image/jpeg',
            ]);
            $images = [];
            for ($j = 0; $j <= 4; $j++) {
                $images[] = [
                    'article_id' => $article->id,
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
        for ($i = 0; $i < 5; $i++) {
            Article::create([
                'author' => $this->faker->randomElement($this->usersId),
                'title' => $this->faker->sentence,
                'highlight' => $this->faker->sentence,
                'content' => $this->faker->text,
                'category_id' => $this->faker->randomElement($this->categoryIds),
            ]);
        }
    }

    public function run(): void
    {
        $files = collect(Storage::disk('public')->allFiles($this->path))
            ->filter(function ($file) {
                return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
            })->values()->all();
        $this->createArticles($files);
    }
}
