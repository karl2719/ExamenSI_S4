
PRAGMA foreign_keys = ON;

CREATE TABLE operateurs (
    id_operateur    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT    NOT NULL,
    code            TEXT    NOT NULL UNIQUE,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prefixes (
    id_prefixe      INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         TEXT    NOT NULL UNIQUE,
    id_operateur    INTEGER NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id_operateur)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE types_operation (
    id_type         INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle         TEXT    NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);

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

CREATE TABLE clients (
    id_client         INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone  TEXT    NOT NULL UNIQUE,
    id_operateur      INTEGER NOT NULL,
    date_creation     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id_operateur)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE transactions (
    id_transaction          INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client_emetteur      INTEGER NOT NULL,
    id_type                 INTEGER NOT NULL,
    montant                 DECIMAL(15,2) NOT NULL,
    frais                   DECIMAL(15,2) NOT NULL DEFAULT 0,
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

INSERT INTO types_operation (code, libelle) VALUES
    ('DEPOT', 'Depot'),
    ('RETRAIT', 'Retrait'),
    ('TRANSFERT', 'Transfert');

INSERT INTO operateurs (nom, code) VALUES ('Telma', 'TLM');
INSERT INTO prefixes (prefixe, id_operateur) VALUES
    ('033', 1),
    ('037', 1);

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