-- Migrasi komponen lengkap halaman seminar
-- Aman dijalankan ulang pada MariaDB 10.11+.

ALTER TABLE seminars
  ADD COLUMN IF NOT EXISTS deadline_label VARCHAR(255) NULL AFTER deadline_date,
  ADD COLUMN IF NOT EXISTS call_for_paper TEXT NULL AFTER theme,
  ADD COLUMN IF NOT EXISTS booklet_link VARCHAR(255) NULL AFTER submission_link;

ALTER TABLE seminar_speakers
  ADD COLUMN IF NOT EXISTS image VARCHAR(255) NULL AFTER affiliation;

ALTER TABLE seminar_schedules
  ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER schedule_date;

CREATE TABLE IF NOT EXISTS seminar_slides (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  title VARCHAR(255) NULL,
  caption VARCHAR(255) NULL,
  image VARCHAR(255) NOT NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_slides_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS seminar_partners (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  role ENUM('host','cohost') NOT NULL DEFAULT 'cohost',
  name VARCHAR(255) NOT NULL,
  logo VARCHAR(255) NOT NULL,
  website VARCHAR(255) NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_partners_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS seminar_fees (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  category VARCHAR(100) NOT NULL,
  item VARCHAR(255) NOT NULL,
  amount BIGINT(20) UNSIGNED NULL,
  note VARCHAR(255) NULL,
  is_closed TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_fees_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS seminar_registrations (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  fee_id INT(11) NULL,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  affiliation VARCHAR(255) NULL,
  paper_title VARCHAR(255) NULL,
  status ENUM('pending','confirmed','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY seminar_email (seminar_id, email),
  KEY fee_id (fee_id),
  CONSTRAINT seminar_registrations_seminar_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE,
  CONSTRAINT seminar_registrations_fee_fk FOREIGN KEY (fee_id) REFERENCES seminar_fees (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
