-- Migrasi v3: subtema, berkas pendaftaran, dan status lunas.
-- Aman dijalankan ulang (IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS seminar_subthemes (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  name VARCHAR(255) NOT NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_subthemes_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE seminar_registrations
  ADD COLUMN IF NOT EXISTS subtheme VARCHAR(255) NULL AFTER paper_title,
  ADD COLUMN IF NOT EXISTS abstract_file VARCHAR(255) NULL AFTER subtheme,
  ADD COLUMN IF NOT EXISTS receipt_file VARCHAR(255) NULL AFTER abstract_file,
  ADD COLUMN IF NOT EXISTS is_paid TINYINT(1) NOT NULL DEFAULT 0 AFTER status,
  ADD COLUMN IF NOT EXISTS paid_at TIMESTAMP NULL AFTER is_paid;
