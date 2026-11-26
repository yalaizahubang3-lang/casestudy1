CREATE DATABASE IF NOT EXISTS plant_nursery_db;

USE plant_nursery_db;

CREATE TABLE IF NOT EXISTS plants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plant_name VARCHAR(150) NOT NULL,
    variety VARCHAR(100) NOT NULL,
    growing_area VARCHAR(50) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO plants (plant_name, variety, growing_area, quantity) VALUES
('Tomato', 'Cherry', 'Seedling Bed 1', 3),
('Eggplant', 'Long Purple', 'Seedling Bed 2', 15),
('Pepper', 'Bell Pepper', 'Growing Area A', 8);
