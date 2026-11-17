CREATE DATABASE labweb;
USE labweb;

CREATE TABLE condicoes_saude (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
descricao TEXT,
orientacoes_preparo TEXT
) ENGINE=InnoDB;

CREATE TABLE exames (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
descricao TEXT,
instrucoes_preparo TEXT
) ENGINE=InnoDB;

CREATE TABLE condicao_exame (
condicao_id INT NOT NULL,
exame_id INT NOT NULL,
PRIMARY KEY (condicao_id, exame_id),
FOREIGN KEY (condicao_id) REFERENCES condicoes_saude(id) ON DELETE CASCADE,
FOREIGN KEY (exame_id) REFERENCES exames(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE laboratorios (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
endereco VARCHAR(255) NOT NULL,
bairro VARCHAR(80) NOT NULL,
telefone VARCHAR(20),
tempo_entrega_dias INT DEFAULT 2
) ENGINE=InnoDB;

CREATE TABLE laboratorio_exame (
laboratorio_id INT NOT NULL,
exame_id INT NOT NULL,
preco_particular DECIMAL(10,2) NOT NULL,
PRIMARY KEY (laboratorio_id, exame_id),
FOREIGN KEY (laboratorio_id) REFERENCES laboratorios(id) ON DELETE CASCADE,
FOREIGN KEY (exame_id) REFERENCES exames(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE convenios (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE laboratorio_convenio (
laboratorio_id INT NOT NULL,
convenio_id INT NOT NULL,
PRIMARY KEY (laboratorio_id, convenio_id),
FOREIGN KEY (laboratorio_id) REFERENCES laboratorios(id) ON DELETE CASCADE,
FOREIGN KEY (convenio_id) REFERENCES convenios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO condicoes_saude (nome, descricao, orientacoes_preparo) VALUES
('Hipotireoidismo', 'Diminuicao da producao dos hormonios da tireoide, causando cansaco e
ganho de peso.', 'Realizar a coleta preferencialmente no periodo da manha.'),
('Anemia Ferropriva', 'Reducao dos globulos vermelhos por deficiencia de ferro no organismo.',
'Evitar consumo de bebidas alcoolicas 24h antes da coleta.');

INSERT INTO exames (nome, descricao, instrucoes_preparo) VALUES
('TSH (Hormonio Tireoestimulante)', 'Avalia o funcionamento geral da glandula tireoide.', 'Jejum
recomendado de 4 horas.'),
('T4 Livre', 'Mede a quantidade de tiroxina livre no sangue.', 'Jejum recomendado de 4 horas.'),
('T3 Total', 'Mede a quantidade total de triiodotironina.', 'Jejum recomendado de 4 horas.'),
('Hemograma Completo', 'Avalia as celulas do sangue (hemacias, leucocitos e plaquetas).', 'Jejum
recomendado de 3 a 4 horas.'),
('Ferritina Serica', 'Mede a quantidade de ferro armazenada no corpo.', 'Jejum recomendado de 8
horas.');

INSERT INTO condicao_exame (condicao_id, exame_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 4), (2, 5);

INSERT INTO laboratorios (nome, endereco, bairro, telefone, tempo_entrega_dias) VALUES
('Laboratorio BioAnalise', 'Av. Central, 100', 'Centro', '(11) 3333-1111', 1),
('Slab Diagnosticos', 'Rua das Flores, 500', 'Jardim America', '(11) 4444-2222', 2);

INSERT INTO laboratorio_exame (laboratorio_id, exame_id, preco_particular) VALUES
(1, 1, 35.00), (1, 2, 38.00), (1, 3, 30.00), (1, 4, 25.00), (1, 5, 45.00),
(2, 1, 40.00), (2, 2, 40.00), (2, 3, 35.00), (2, 4, 20.00), (2, 5, 40.00);

INSERT INTO convenios (nome) VALUES
('Particular'), ('Unimed'), ('Bradesco Saude'), ('SulAmerica'), ('Amil');

INSERT INTO laboratorio_convenio (laboratorio_id, convenio_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 4), (2, 5);