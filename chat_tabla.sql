-- Agregar a YEDI.sql (ejecutar en tu BD)
USE YEDI;

CREATE TABLE IF NOT EXISTS mensajes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    de_usuario  VARCHAR(50) NOT NULL,
    para_usuario VARCHAR(50) NOT NULL,
    mensaje     TEXT NOT NULL,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_conversacion (de_usuario, para_usuario),
    INDEX idx_fecha (fecha)
);
