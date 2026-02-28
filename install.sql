CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);

CREATE TABLE fiches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_incident DATE,
    heure_incident TIME,
    lieu TEXT,
    travailleurs TEXT,
    tiers TEXT,
    description TEXT,
    nature TEXT,
    consequences_travailleur TEXT,
    consequences_entreprise TEXT,
    actions TEXT,
    mesures TEXT,
    sign_trav_nom VARCHAR(100),
    sign_trav_date DATE,
    sign_resp_nom VARCHAR(100),
    sign_resp_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
