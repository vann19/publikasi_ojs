-- Migrasi: tabel bank transfer untuk seminar.
-- Aman dijalankan ulang (IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS seminar_bank_transfers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  seminar_id INT(11) NOT NULL,
  bank_name VARCHAR(100) NOT NULL,
  account_number VARCHAR(50) NOT NULL,
  account_name VARCHAR(255) NULL,
  sort_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY seminar_id (seminar_id),
  CONSTRAINT seminar_bank_transfers_fk FOREIGN KEY (seminar_id) REFERENCES seminars (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
