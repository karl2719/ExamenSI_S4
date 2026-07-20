-- =========================================================
-- Donnees de test - Mobile Money
-- A executer APRES database_sqlite.sql (schema + donnees de base)
-- =========================================================

PRAGMA foreign_keys = ON;

-- =========================================================
-- Operateurs supplementaires (Telma id=1 deja cree)
-- =========================================================
INSERT INTO operateurs (nom, code) VALUES
    ('Orange', 'ORG'),
    ('Airtel', 'ATL');
-- id_operateur : Telma=1, Orange=2, Airtel=3

INSERT INTO prefixes (prefixe, id_operateur) VALUES
    ('032', 2),   -- Orange
    ('034', 3);   -- Airtel
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
-- Verification rapide
-- =========================================================
-- SELECT * FROM clients;
-- SELECT * FROM transactions;
-- SELECT c.numero_telephone, SUM(t.frais) AS frais_generes
--   FROM transactions t JOIN clients c ON c.id_client = t.id_client_emetteur
--   GROUP BY c.id_client;
