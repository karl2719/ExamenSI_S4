# BDD :

# BACK-OFFICE : 
## Models
 1. [ ] `OperateurModel` : CRUD operateurs [ETU4061]
 2. [ ] `PrefixeModel` : CRUD prefixes par operateur [ETU4061]
 3. [ ] `TypeOperationModel` : CRUD types d'operation [ETU4061]
 4. [ ] `BaremeFraisModel` : CRUD barèmes de frais + methode [ETU4061]
## Controllers
1. [ ] `AdminOperateurController` : gestion operateurs/prefixes [ETU4061]
2. [ ] `AdminBaremeController` : gestion des tranches de frais (modifiable) [ETU4061]
3. [ ] `AdminStatsController` : situation gain (agregation frais), situation comptes clients [ETU4061]
## Views
1. [ ] Formulaires admin (prefixes, types d'operation, barèmes) [ETU4061]
2. [ ] Tableau de bord gains (par type, par periode) [ETU4061]
3. [ ] Liste des comptes clients avec soldes [ETU4061]

# FRONT-OFFICE :
## Models
1. [ ] `ClientModel` : recherche par telephone, creation automatique
2. [ ] `TransactionModel` : insertion depot/retrait/transfert, historique par client

## Controllers
1. [ ] `AuthController` : login automatique par numero (pas d'inscription)
2. [ ] `CompteController` : afficher solde
3. [ ] `TransactionController` : depot, retrait, transfert — utilise
4. [ ] `BaremeFraisModel::getFraisApplicable()` 
## Views
1. [ ] ecran de connexion (saisie numero)
2. [ ] Dashboard client (solde + boutons operations)
3. [ ] Historique des transactions