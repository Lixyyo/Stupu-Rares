-- Rulează acest fișier o singură dată în baza ta de date (studenti)
-- ca să suporte: produse pe categorii + parole hash + comenzi cu mai multe produse.

-- 1) Produse: categorie pentru paginile Tutun/Filtre/Foițe
ALTER TABLE Produse
  ADD COLUMN categorie VARCHAR(20) NOT NULL DEFAULT 'tutun';

-- 2) User: parola mai lungă pentru password_hash()
ALTER TABLE User
  MODIFY parola VARCHAR(255) NOT NULL;

-- 3) Comanda: grup pentru comenzi cu mai multe produse + cantitate + dată
ALTER TABLE Comanda
  ADD COLUMN id_grup BIGINT NOT NULL,
  ADD COLUMN cantitate INT NOT NULL DEFAULT 1,
  ADD COLUMN data_c DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE INDEX idx_grup ON Comanda (id_grup);

-- Exemplu: setează categoria pe produse existente
-- UPDATE Produse SET categorie='tutun'  WHERE id_produs IN (1,2,3);
-- UPDATE Produse SET categorie='filtre' WHERE id_produs IN (4,5,6);
-- UPDATE Produse SET categorie='foite'  WHERE id_produs IN (7,8,9);
