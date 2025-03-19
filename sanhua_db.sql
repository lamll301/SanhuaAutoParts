-- CREATE DATABASE sanhua_dev;
-- DROP DATABASE sanhua_dev;

CREATE TABLE test (
  id BIGSERIAL NOT NULL PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  status INTEGER DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users (username, password, email) VALUES
('sdelort0', 'lC3`|R<>k,h<z{Rd', 'ozipsell0@bloomberg.com'),
('dlevane1', 'kI2/EbrU', 'wleggatt1@exblog.jp'),
('dparoni2', 'kK7\B\A9nB|CIjJ', 'penbury2@discuz.net'),
('nmaior3', 'nH0~eb|@WY', 'dgoodwins3@webs.com'),
('oiglesias4', 'vH8{bkukDy_TvC', 'lprinett4@npr.org'),
('cmatschuk5', 'zJ3#}Y$V?$', 'lmccrum5@exblog.jp'),
('shavesides6', 'zG7`j=|57pCs', 'tmacaree6@amazon.com'),
('kkiloh7', 'cV9`0M82R7Fe=', 'kivell7@blogtalkradio.com'),
('kdevlin8', 'sM7{&55*', 'dbamell8@sciencedaily.com'),
('lbignal9', 'xG0+FwI{', 'cangelini9@com.com'),
('msailsa', 'xO7=O1CA', 'sbeetlesa@lulu.com'),
('lbinfordb', 'cR6{{D!k~"1', 'kmaplesdenb@live.com'),
('kshaughnessyc', 'jW5~gXKLFNCF/n', 'fguitelc@photobucket.com'),
('rhenrionotd', 'aO1%K>p?w9qB', 'sjimed@skype.com'),
('piorie', 'sR2~z<*Is`p+', 'tmuzzulloe@vk.com'),
('kgirodonf', 'jC9+9(K$4`', 'otilzeyf@japanpost.jp'),
('mclinkardg', 'bO2{G8qd>P(c4', 'ebeardowg@ca.gov'),
('florandh', 'dO7*M$\!LSh~', 'gwindrossh@hud.gov'),
('sdmitrienkoi', 'lU1>uPNNwC', 'sfitteri@guardian.co.uk'),
('dluckhamj', 'uZ6,zL`3KxP+!h', 'sappleyardj@youtube.com');

SELECT * FROM test;

-- Xóa hết dữ liệu và đặt id về 1
TRUNCATE TABLE permissions RESTART IDENTITY CASCADE;

INSERT INTO permissions (name, description, created_at, updated_at) VALUES
('read_data', 'Có quyền đọc dữ liệu.', NOW(), NOW()),
('write_data', 'Có quyền tạo hoặc ghi dữ liệu.', NOW(), NOW()),
('delete_data', 'Có quyền xóa dữ liệu.', NOW(), NOW()),
('edit_data', 'Có quyền chỉnh sửa dữ liệu.', NOW(), NOW()),
('approve_report', 'Có quyền phê duyệt báo cáo.', NOW(), NOW());



/* 
  *** Các câu lệnh trong laravel
	run migrate: php artisan migrate
  update migrate: php artisan migrate:refresh --seed
  create migrate: php artisan make:migration create_ten_bang_so_nhieu_table

  run be: php artisan serve

  *** Các câu lệnh trong postgres sql cmd
  psql -U postgres -W -h localhost -p 5432 sanhua_dev
  
  \c ten-db: access db
  \l: list db
	\d: list table
		\d ten-bang: table structure details
	\q: quit
	\i 'duong-dan-toi-file/db.sql': run file sql


  *** Sự khác biệt giữa on delete set null và cascade

  set null khi bản ghi tham chiếu bị xóa khóa ngoại được đặt thành null
  cascade khi bản ghi tham chiếu bị xóa tất cả bản ghi liên quan cũng bị xóa theo


*/

