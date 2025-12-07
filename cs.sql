CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    conteudo LONGTEXT NOT NULL,
    curtidas INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    usuario_id INT NOT NULL,
    conteudo LONGTEXT NOT NULL,
    curtidas INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ocorrencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    endereco VARCHAR(255),
    descricao TEXT NOT NULL,
    foto_url VARCHAR(255),
    curtidas INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    ocorrencia_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_voto (usuario_id, ocorrencia_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (ocorrencia_id) REFERENCES ocorrencias(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_ocorrencia (ocorrencia_id)
);