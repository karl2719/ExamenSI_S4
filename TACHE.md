# BDD :

# BACK-OFFICE : 
## Models
 1. [x] `OperateurModel` : CRUD operateurs [ETU4061]
 2. [x] `PrefixeModel` : CRUD prefixes par operateur [ETU4061]
 3. [x] `TypeOperationModel` : CRUD types d'operation [ETU4061]
 4. [x] `BaremeFraisModel` : CRUD barèmes de frais + methode [ETU4061]
## Controllers
1. [x] `AdminOperateurController` : gestion operateurs/prefixes [ETU4061]
2. [x] `AdminBaremeController` : gestion des tranches de frais (modifiable) [ETU4061]
3. [x] `AdminStatsController` : situation gain (agregation frais), situation comptes clients [ETU4061]
## Views
1. [x] Formulaires admin (prefixes, types d'operation, barèmes) [ETU4061]
2. [x] Tableau de bord gains (par type, par periode) [ETU4061]
3. [x] Liste des comptes clients avec soldes [ETU4061]

# FRONT-OFFICE :
## Models
1. [x] `ClientModel` : recherche par telephone, creation automatique
2. [x] `TransactionModel` : insertion depot/retrait/transfert, historique par client

## Controllers
1. [x] `AuthController` : login automatique par numero (pas d'inscription)
2. [x] `CompteController` : afficher solde
3. [x] `TransactionController` : depot, retrait, transfert — utilise
4. [x] `BaremeFraisModel::getFraisApplicable()` 
## Views
1. [x] ecran de connexion (saisie numero)
2. [x] Dashboard client (solde + boutons operations)
3. [x] Historique des transactions