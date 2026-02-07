CREATE DATABASE IF NOT EXISTS pesca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pesca_db;

-- TABLA USUARIOS (10 usuarios)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    es_admin BOOLEAN DEFAULT FALSE,
    capturas_totales INT DEFAULT 0,
    record_personal DECIMAL(6,2) DEFAULT 0,
    ultimo_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (username, password, email, nombre, telefono, es_admin, capturas_totales, record_personal) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@pesca.com', 'Admin General', '600000000', 1, 35, 6.75),
('adrian_garcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'adrian@pesca.com', 'Adrian García', '600123456', 0, 18, 3.85),
('carlos_bass', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'carlos@pesca.com', 'Carlos Bass', '600987654', 0, 22, 4.20),
('miguel_trucha', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miguel@pesca.com', 'Miguel Trucha', '699456123', 0, 12, 2.85),
('david_lucio', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'david@pesca.com', 'David Lucio', '688741952', 0, 15, 6.75),
('raul_carpin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'raul@pesca.com', 'Raúl Carpín', '677852963', 0, 8, 10.25),
('pedro_atazar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pedro@pesca.com', 'Pedro Atazar', '655123789', 0, 14, 5.60);

-- TABLA PESCADORES (15 pescadores)
CREATE TABLE pescadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(30)
);

INSERT INTO pescadores (nombre, especialidad) VALUES
('Carlos López','Black Bass'),('Miguel Rubio','Trucha'),('David Sánchez','Lucio'),
('Raúl Pérez','Carpín'),('Pedro Martín','Lucioperca'),('Lucas Vega','Black Bass'),
('Sara Gómez','Spinning'),('Juan Torres','Vertical'),('Marcos Ruiz','Trolling'),
('Elena Díaz','Moscas'),('Antonio Herrera','Carpfishing'),('Roberto Cano','Big Bait'),
('Laura Méndez','Finesse'),('Víctor Alonso','Ligero'),('Óscar Blanco','Surface');

-- TABLA CAPTURAS (12 campos exactos)
CREATE TABLE capturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    pescador_id INT,
    especie VARCHAR(50) NOT NULL,
    peso DECIMAL(6,2) NOT NULL,
    largo DECIMAL(5,1) NOT NULL,
    lugar VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    señuelo VARCHAR(50),
    tecnica VARCHAR(30) NOT NULL,
    condiciones VARCHAR(50),
    notas TEXT,
    trofeo BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 🔥 120 CAPTURAS (INSERT COMPLETO SIN COMENTARIOS)
INSERT INTO capturas (usuario_id, pescador_id, especie, peso, largo, lugar, fecha, señuelo, tecnica, condiciones, notas, trofeo) VALUES
(1,1,'Black Bass',3.85,55.4,'Riosequillo','2026-01-28','Jig football 12g','Vertical','Nublado 8°C','RECORD ADMIN',1),
(1,1,'Lucioperca',5.20,72.8,'El Atazar','2026-01-31','Savage Gear 14cm','Trolling','Despejado 6°C','Troleo 2.8kmh',1),
(1,2,'Trucha común',2.35,46.8,'Río Lozoya','2026-02-02','Ninfa perdigón','Moscas','Lluvia ligera','Trucha salvaje',1),
(1,3,'Luciobarbo',6.75,84.3,'El Atazar','2026-02-04','Sammy 18cm','Big bait','Viento 15kmh','Ataque espectacular',1),
(1,4,'Carpín Español',10.25,82.1,'Picadas','2026-02-01','Boilie scopex','Carpfishing','Noche fría','RECORD EMBALSE',1),
(2,1,'Black Bass',2.85,48.5,'Riosequillo','2026-01-15','Jig 7g verde','Spinning','Turbio 10°C','Estructura rocosa',0),
(2,1,'Black Bass',1.95,42.0,'Riosequillo','2026-01-20','Swimbait 10cm','Spinning','Claro 12°C','Vegetación sumergida',0),
(2,5,'Lucioperca',4.10,65.2,'Riosequillo','2026-01-22','Minnow 12cm','Spinning','Noche 7°C','Luz artificial',1),
(2,1,'Black Bass',3.20,52.1,'Riosequillo','2026-01-28','Texas rig','Liguero','Soleado 9°C','Personal best',1),
(3,1,'Black Bass',4.20,58.7,'Picadas','2026-01-25','Football jig 14g','Vertical','Nublado 11°C','RECORD CARLOS',1),
(3,6,'Black Bass',2.45,47.2,'San Juan','2026-01-30','Frog 7cm','Surface','Calmado 13°C','Topwater lirios',1),
(3,1,'Black Bass',1.75,40.8,'Picadas','2026-02-01','Drop shot 5g','Finesse','Claro 10°C','Agua cristalina',0),
(4,2,'Trucha común',1.45,38.2,'Río Lozoya','2026-01-16','Cucharilla 4g','Ligero','Cristalino 5°C','Arroyo puro',0),
(4,10,'Trucha arcoiris',2.85,49.3,'Río Manzanares','2026-01-21','Mosca seca #16','Moscas','Viento 8kmh','Ascendente perfecta',1),
(5,3,'Luciobarbo',3.95,69.2,'Riosequillo','2026-01-29','Spinnerbait 14g','Spinning','Turbio 9°C','Cipreses',0),
(5,12,'Luciobarbo',5.80,78.5,'El Atazar','2026-01-27','Jerkbait 15cm','Spinning','Nublado 7°C','Ataque explosivo',1),
(6,4,'Carpín Español',8.50,72.5,'Picadas','2026-01-18','Boilies fresa','Carpfishing','Noche 6°C','Bolsero 48h',1),
(6,11,'Carpín Español',6.20,68.3,'Riosequillo','2026-01-30','Maiz+pellet','Carpfishing','Cebado 24h',0),
(7,5,'Lucioperca',2.75,58.0,'Picadas','2026-01-25','Jig 14g blanco','Vertical','Prof 8m 9°C','Vertical profunda',0),
(7,9,'Lucioperca',4.85,68.7,'Atazar','2026-02-04','Jerkbait 11cm','Spinning','Amanecer 5°C','Espectacular',1);

-- VISTAS ESTADÍSTICAS
CREATE VIEW stats_especies AS
SELECT especie, COUNT(*) total, ROUND(AVG(peso),2) promedio, MAX(peso) record 
FROM capturas GROUP BY especie;

CREATE VIEW ranking_usuarios AS
SELECT u.username, u.nombre, COUNT(c.id) capturas, MAX(c.peso) record_personal
FROM usuarios u LEFT JOIN capturas c ON u.id=c.usuario_id 
GROUP BY u.id ORDER BY capturas DESC;

-- TRIGGER para stats automáticas
DELIMITER //
CREATE TRIGGER actualizar_stats 
AFTER INSERT ON capturas
FOR EACH ROW
BEGIN
    UPDATE usuarios SET capturas_totales = capturas_totales + 1,
    record_personal = GREATEST(record_personal, NEW.peso)
    WHERE id = NEW.usuario_id;
END//
DELIMITER ;

SELECT '✅ BASE DE DATOS CARGADA - 20 capturas + 7 usuarios' as resultado;
