create database YEDI;
use YEDI;

create table usuarios(
    id int auto_increment primary key,
    usuario varchar(50) unique not null, -- Nuevo campo usuario agregado
    nombre varchar(100),
    password varchar(255),
    rol enum('cliente','vendedor') not null
);

create table productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10,2),
    
    -- Imagen guardada como ruta o nombre del archivo
    imagen VARCHAR(255),
    
    vendedor_id INT,
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id)
);


