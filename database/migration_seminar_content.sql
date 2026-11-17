-- Migrasi: teks setiap section halaman seminar (dikelola dari admin/CMS).
-- Aman dijalankan ulang (IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS seminar_content (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  content_key VARCHAR(100) NOT NULL,
  content_value TEXT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY seminar_content_key (seminar_id, content_key),
  CONSTRAINT seminar_content_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
