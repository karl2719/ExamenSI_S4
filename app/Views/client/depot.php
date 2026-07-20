<!-- ==========================================================
     Vue : Formulaire de depot
     
     Permet au client de deposer de l'argent sur son compte.
     Le depot est automatique (statut = reussi immediatement)
     et sans frais.
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depot - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-4">Faire un depot</h1>

        <!-- Messages flash d'erreur -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de depot -->
        <!-- Envoie en POST vers /transaction/depot (TransactionController::depot) -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= base_url('transaction/depot') ?>" method="post">

                    <!-- Champ montant a deposer -->
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant (Ar) :</label>
                        <input
                            type="number"
                            name="montant"
                            id="montant"
                            class="form-control"
                            min="1"
                            placeholder="Ex: 50000"
                            required
                        >
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-success">Deposer</button>

                </form>
            </div>
        </div>

        <!-- Information : le depot est automatique et gratuit -->
        <p class="text-muted mt-3"><em>Le depot est automatique et sans frais.</em></p>

        <hr>

        <!-- Retour au dashboard -->
        <a href="<?= base_url('compte') ?>" class="btn btn-secondary">&larr; Retour au dashboard</a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
