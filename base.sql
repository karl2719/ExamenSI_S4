-- =========================================================
-- Base de donnees : Mobile Money (Version 1)
-- Moteur : SQLite3
-- =========================================================

PRAGMA foreign_keys = ON;

-- ---------------------------------------------------------
-- Table : operateurs
-- ---------------------------------------------------------
CREATE TABLE operateurs (
    id_operateur    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT    NOT NULL,
    code            TEXT    NOT NULL UNIQUE,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table : prefixes
-- ---------------------------------------------------------
CREATE TABLE prefixes (
    id_prefixe      INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         TEXT    NOT NULL UNIQUE,
    id_operateur    INTEGER NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id_operateur)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- ---------------------------------------------------------
-- Table : types_operation
-- ---------------------------------------------------------
CREATE TABLE types_operation (
    id_type         INTEGER PRIMARY KEY AUTOINCREMENT,
    code            TEXT    NOT NULL UNIQUE,   -- DEPOT, RETRAIT, TRANSFERT
    libelle         TEXT    NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- Table : baremes_frais
-- ---------------------------------------------------------
CREATE TABLE baremes_frais (
    id_bareme       INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type         INTEGER NOT NULL,
    montant_min     DECIMAL(15,2) NOT NULL,
    montant_max     DECIMAL(15,2) NOT NULL,
    frais           DECIMAL(15,2) NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_type) REFERENCES types_operation(id_type)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX idx_bareme_type_montant ON baremes_frais (id_type, montant_min, montant_max);

-- ---------------------------------------------------------
-- Table : clients
-- ---------------------------------------------------------
CREATE TABLE clients (
    id_client         INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone  TEXT    NOT NULL UNIQUE,
    
    id_operateur      INTEGER NOT NULL,
    pourcentageEpargne DECIMAL,
    date_creation     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id_operateur)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------------------
-- Table : transactions
-- ---------------------------------------------------------
CREATE TABLE transactions (
    id_transaction          INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client_emetteur      INTEGER NOT NULL,
    id_client_destinataire  INTEGER NULL,          -- rempli uniquement pour un transfert
    id_type                 INTEGER NOT NULL,
    montant                 DECIMAL(15,2) NOT NULL,
    frais                   DECIMAL(15,2) NOT NULL DEFAULT 0,
    montant_total           DECIMAL(15,2) NOT NULL,   -- montant + frais
    date_transaction        DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut                  TEXT NOT NULL DEFAULT 'en_attente'
        CHECK (statut IN ('en_attente','reussi','echoue')),
    FOREIGN KEY (id_client_emetteur) REFERENCES clients(id_client)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_client_destinataire) REFERENCES clients(id_client)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_type) REFERENCES types_operation(id_type)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE INDEX idx_transaction_emetteur ON transactions (id_client_emetteur);
CREATE INDEX idx_transaction_destinataire ON transactions (id_client_destinataire);
CREATE INDEX idx_transaction_date ON transactions (date_transaction);

-- =========================================================
-- Donnees de reference (types d'operation)
-- =========================================================
INSERT INTO types_operation (code, libelle) VALUES
    ('DEPOT', 'Depot'),
    ('RETRAIT', 'Retrait'),
    ('TRANSFERT', 'Transfert');


-- =========================================================
-- Exemple : bareme de frais pour le retrait (issu du tableau)
-- =========================================================
INSERT INTO baremes_frais (id_type, montant_min, montant_max, frais) VALUES
    (2, 100,      1000,     50),
    (2, 1001,     5000,     50),
    (2, 5001,     10000,    100),
    (2, 10001,    25000,    200),
    (2, 25001,    50000,    400),
    (2, 50001,    100000,   800),
    (2, 100001,   250000,   1500),
    (2, 250001,   500000,   1500),
    (2, 500001,   1000000,  2500),
    (2, 1000001,  2000000,  3000);

-- ---------------------------------------------------------
-- Table : admin (backoffice)
-- ---------------------------------------------------------
CREATE TABLE admin (
    id_admin        INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_utilisateur TEXT    NOT NULL UNIQUE,
    mot_de_passe    TEXT    NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Admin par defaut (login: admin / mdp: admin)
INSERT INTO admin (nom_utilisateur, mot_de_passe) VALUES ('admin', 'admin');

-- =========================================================
-- Donnees de test - Mobile Money
-- A executer APRES database_sqlite.sql (schema + donnees de base)
-- =========================================================

PRAGMA foreign_keys = ON;

-- =========================================================
-- Operateurs supplementaires (Telma id=1 deja cree)
-- =========================================================
INSERT INTO operateurs (nom, code) VALUES
    ('Telma', 'TLM'),
    ('Orange', 'ORG'),
    ('Airtel', 'ATL');
-- id_operateur : Telma=1, Orange=2, Airtel=3

INSERT INTO prefixes (prefixe, id_operateur) VALUES
    ('034', 1),
    ('038', 1),
    ('032', 2),   -- Orange
    ('037', 2),   -- Orange
    ('033', 3);   -- Airtel
-- Telma (033, 037) deja inseres dans le script principal

-- =========================================================
-- Baremes de frais manquants (DEPOT id=1, TRANSFERT id=3)
-- Le bareme du RETRAIT (id_type=2) est deja dans le script principal
-- =========================================================
INSERT INTO baremes_frais (id_type, montant_min, montant_max, frais) VALUES
    (1, 100,      1000,     0),
    (1, 1001,     5000,     0),
    (1, 5001,     10000,    0),
    (1, 10001,    50000,    0),
    (1, 50001,    2000000,  0);

INSERT INTO baremes_frais (id_type, montant_min, montant_max, frais) VALUES
    (3, 100,      1000,     30),
    (3, 1001,     5000,     50),
    (3, 5001,     10000,    80),
    (3, 10001,    25000,    150),
    (3, 25001,    50000,    300),
    (3, 50001,    100000,   600),
    (3, 100001,   250000,   1200),
    (3, 250001,   500000,   1200),
    (3, 500001,   1000000,  2000),
    (3, 1000001,  2000000,  2500);

-- =========================================================
-- Clients de test (numeros conformes aux prefixes crees)
-- =========================================================
INSERT INTO clients (numero_telephone, id_operateur) VALUES
    ('0331234567', 1),   -- Telma
    ('0372345678',  1),   -- Telma
    ('0323456789', 2),   -- Orange
    ('0324567890',  2),   -- Orange
    ('0341234567', 3),   -- Airtel
    ('0345678901', 3);   -- Airtel, solde vide
-- id_client : 1..6 dans l'ordre ci-dessus

-- =========================================================
-- Transactions de test
-- =========================================================

-- Depot (id_client_destinataire = NULL, credite le solde de l'emetteur)
INSERT INTO transactions
    (id_client_emetteur, id_client_destinataire, id_type, montant, frais, montant_total, statut)
VALUES
    (1, NULL, 1, 50000, 0, 50000, 'reussi'),
    (3, NULL, 1, 200000, 0, 200000, 'reussi');

-- Retrait (id_client_destinataire = NULL)
INSERT INTO transactions
    (id_client_emetteur, id_client_destinataire, id_type, montant, frais, montant_total, statut)
VALUES
    (2, NULL, 2, 10000, 100, 10100, 'reussi'),
    (5, NULL, 2, 300000, 800, 300800, 'echoue');

-- Transfert (id_client_destinataire renseigne)
INSERT INTO transactions
    (id_client_emetteur, id_client_destinataire, id_type, montant, frais, montant_total, statut)
VALUES
    (1, 2, 3, 20000, 150, 20150, 'reussi'),
    (3, 4, 3, 15000, 150, 15150, 'reussi'),
    (5, 6, 3, 100000, 1200, 101200, 'en_attente');

-- =========================================================
-- Version 2 : Modifications backoffice operateur
-- =========================================================

-- Ajout colonne id_operateur dans la table admin
-- Permet de savoir a quel operateur appartient l'admin connecte
ALTER TABLE admin ADD COLUMN id_operateur INTEGER REFERENCES operateurs(id_operateur);

-- L'admin par defaut (id=1) est lie a Telma (id_operateur=1)
UPDATE admin SET id_operateur = 1 WHERE id_admin = 1;

-- ---------------------------------------------------------
-- Table : commissions_inter_operateur
-- Stocke le pourcentage de commission supplementaire
-- pour les transferts vers un autre operateur
-- ---------------------------------------------------------
CREATE TABLE commissions_inter_operateur (
    id_commission        INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur_source  INTEGER NOT NULL,        -- l'operateur qui envoie
    id_operateur_dest    INTEGER NOT NULL,        -- l'operateur qui recoit
    pourcentage          DECIMAL(5,2) NOT NULL DEFAULT 0,  -- % de commission en plus
    created_at           DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at           DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur_source) REFERENCES operateurs(id_operateur)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_operateur_dest) REFERENCES operateurs(id_operateur)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE(id_operateur_source, id_operateur_dest)
);

-- Donnees de test : commissions inter-operateur
INSERT INTO commissions_inter_operateur (id_operateur_source, id_operateur_dest, pourcentage) VALUES
    (1, 2, 2.00),   -- Telma vers Orange : 2%
    (1, 3, 3.00);   -- Telma vers Airtel : 3%

CREATE TABLE reduction (
    id_reduction       INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur INTEGERINTEGER NOT NULL, 
    pourcentage          DECIMAL(5,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_operateuR) REFERENCES operateurs(id_operateur)
        ON DELETE CASCADE ON UPDATE CASCADE
);



INSERT INTO reduction (id_operateur , pourcentage) VALUES 
    (1,50.00),
    (2,50.00),
    (3,50.00);