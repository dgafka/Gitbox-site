-- usuwanie zbędnych powiązań
ALTER TABLE menu
    DROP COLUMN id_category;

ALTER TABLE content
    DROP COLUMN id_category;

-- utworzenie nowej tabeli
CREATE TABLE IF NOT EXISTS content_category (
  id_content INT NOT NULL,
  id_category INT NOT NULL,
  CONSTRAINT CONTENT_CATEGORY_FK_id_content FOREIGN KEY (id_content) REFERENCES content(id) ON DELETE CASCADE,
  CONSTRAINT CONTENT_CATEGORY_FK_id_category FOREIGN KEY (id_category) REFERENCES category(id) ON DELETE CASCADE,
  PRIMARY KEY(id_content, id_category)
);

-- wyczyszczenie tabeli z kategoriami i zapełnienie jej nowymi rekordami
DELETE FROM category;

INSERT INTO category(name, description) VALUES
('Moda', 'Moda'),
('Biografia', 'Biografia'),
('Kultura', 'Kultura'),
('Sztuka', 'Sztuka'),
('Nauka', 'Nauka'),
('Społeczeństwo', 'Społeczeństwo'),
('Filmy', 'Filmy'),
('Literatura', 'Literatura'),
('Sport', 'Sport'),
('Komputery', 'Komputery'),
('Informatyka', 'Informatyka'),
('Programowanie', 'Programowanie'),
('Technologie', 'Technologie'),
('Filozofia', 'Filozofia'),
('Nauki ścisłe', 'Nauki ścisłe'),
('Gospodarka', 'Gospodarka'),
('Religia', 'Religia'),
('Historia', 'Historia'),
('Geografia', 'Geografia'),
('Rodzina', 'Rodzina'),
('Polityka', 'Polityka'),
('Fantastyka', 'Fantastyka'),
('Komiksy', 'Komiksy'),
('Miszmasz', 'Miszmasz');