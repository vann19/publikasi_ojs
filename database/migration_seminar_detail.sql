-- Migrasi: detail seminar ala ICoMS (tema, countdown, pembicara, jadwal, biaya)
-- Aman dijalankan ulang (IF NOT EXISTS).

ALTER TABLE seminars
  ADD COLUMN IF NOT EXISTS subtitle VARCHAR(255) NULL AFTER title,
  ADD COLUMN IF NOT EXISTS organizer VARCHAR(255) NULL AFTER subtitle,
  ADD COLUMN IF NOT EXISTS theme TEXT NULL AFTER organizer,
  ADD COLUMN IF NOT EXISTS end_date DATE NULL AFTER date,
  ADD COLUMN IF NOT EXISTS deadline_date DATE NULL AFTER end_date,
  ADD COLUMN IF NOT EXISTS fee_info TEXT NULL AFTER price,
  ADD COLUMN IF NOT EXISTS submission_link VARCHAR(255) NULL AFTER link;

CREATE TABLE IF NOT EXISTS seminar_speakers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  category ENUM('opening','keynote','plenary') NOT NULL DEFAULT 'plenary',
  name VARCHAR(255) NOT NULL,
  title VARCHAR(255) NULL,
  affiliation VARCHAR(255) NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_speakers_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS seminar_schedules (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  label VARCHAR(255) NOT NULL,
  schedule_date VARCHAR(100) NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_schedules_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
