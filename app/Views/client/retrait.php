<!-- ==========================================================
     Vue : Formulaire de retrait
     
     Permet au client de retirer de l'argent de son compte.
     Le retrait est automatique (statut = reussi immediatement).
     Des frais sont appliques selon le bareme en vigueur
     (calcules par BaremeFraisModel::getFraisApplicable).
     Le solde doit etre suffisant pour couvrir montant + frais.
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Faire un retrait</h1>

        <!-- Affichage du solde actuel pour que le client sache combien il peut retirer -->
        <p>Votre solde actuel : <strong class="text-primary"><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></p>

        <!-- Messages flash d'erreur (solde insuffisant, etc.) -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de retrait -->
        <!-- Envoie en POST vers /transaction/retrait (TransactionController::retrait) -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= base_url('transaction/retrait') ?>" method="post">

                    <!-- Champ montant a retirer -->
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant (Ar) :</label>
                        <input
                            type="number"
                            name="montant"
                            id="montant"
                            class="form-control"
                            min="1"
                            placeholder="Ex: 10000"
                            required
                        >
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-warning">Retirer</button>

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
