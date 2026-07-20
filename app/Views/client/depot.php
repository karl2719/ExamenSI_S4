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
</head>
<body>

    <h1>Faire un depot</h1>

    <!-- Messages flash d'erreur -->
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire de depot -->
    <!-- Envoie en POST vers /transaction/depot (TransactionController::depot) -->
    <form action="<?= base_url('transaction/depot') ?>" method="post">

        <!-- Champ montant a deposer -->
        <label for="montant">Montant (Ar) :</label><br>
        <input
            type="number"
            name="montant"
            id="montant"
            min="1"
            placeholder="Ex: 50000"
            required
        ><br><br>

        <!-- Bouton de soumission -->
        <button type="submit">Deposer</button>

    </form>

    <!-- Information : le depot est automatique et gratuit -->
    <p><em>Le depot est automatique et sans frais.</em></p>

    <hr>

    <!-- Retour au dashboard -->
    <a href="<?= base_url('compte') ?>">Retour au dashboard</a>

</body>
</html>
