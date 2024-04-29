-- CRIA BANCO DE DADOS
DROP DATABASE IF EXISTS db_monsters;
CREATE DATABASE IF NOT EXISTS db_monsters;
USE db_monsters;

-- CRIA ELEMENTOS
CREATE TABLE IF NOT EXISTS tb_elements (
    ele_id INT AUTO_INCREMENT PRIMARY KEY,
    ele_name VARCHAR(50) NOT NULL
);

-- CRIA MONSTRINHOS
CREATE TABLE IF NOT EXISTS tb_monsters (
    mon_id INT AUTO_INCREMENT PRIMARY KEY,
    mon_name VARCHAR(100) NOT NULL,
    mon_description TEXT,
    mon_ele_id INT,
    FOREIGN KEY (mon_ele_id) REFERENCES tb_elements(ele_id)
);
