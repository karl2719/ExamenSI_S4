<!-- ==========================================================
     Vue : Formulaire de transfert
     
     Permet au client d'envoyer de l'argent a un autre numero.
     Le transfert necessite :
     - Le numero du destinataire (cree automatiquement si nouveau)
     - Le montant a envoyer
     Des frais sont appliques selon le bareme en vigueur.
     L'emetteur paie montant + frais, le destinataire recoit le montant.
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Faire un transfert</h1>

        <!-- Affichage du solde actuel -->
        <p>Votre solde actuel : <strong class="text-primary"><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></p>

        <!-- Messages flash d'erreur -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de transfert -->
        <!-- Envoie en POST vers /transaction/transfert (TransactionController::transfert) -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= base_url('transaction/transfert') ?>" method="post">

                    <!-- Champ numero du destinataire -->
                    <div class="mb-3">
                        <label for="numero_destinataire" class="form-label">Numero du destinataire :</label>
                        <input
                            type="text"
                            name="numero_destinataire"
                            id="numero_destinataire"
                            class="form-control"
                            placeholder="Ex: 0337654321"
                            maxlength="10"
                            required
                        >
                    </div>

                    <!-- Champ montant a transferer -->
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant (Ar) :</label>
                        <input
                            type="number"
                            name="montant"
                            id="montant"
                            class="form-control"
                            min="1"
                            placeholder="Ex: 25000"
                            required
                        >
                    </div>

                    <!-- Option : inclure les frais de retrait -->
                    <div class="form-check mb-3">
                        <input
                            type="checkbox"
                            name="inclure_frais_retrait"
                            id="inclure_frais_retrait"
                            class="form-check-input"
                            value="1"
                        >
                        <label for="inclure_frais_retrait" class="form-check-label">
                            Inclure les frais de retrait
                        </label>
                        <div class="form-text text-muted">
                            <small>Si coche, vous payez aussi les frais de retrait pour le destinataire.</small>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary">Transferer</button>

                </form>
            </div>
        </div>

        <!-- Information sur les frais -->
        <p class="text-muted mt-3"><em>Des frais seront appliques selon le bareme en vigueur.</em></p>

        <hr>

        <!-- Retour au dashboard -->
        <a href="<?= base_url('compte') ?>" class="btn btn-secondary">&larr; Retour au dashboard</a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
