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
</head>
<body>

    <h1>Faire un retrait</h1>

    <!-- Affichage du solde actuel pour que le client sache combien il peut retirer -->
    <p>Votre solde actuel : <strong><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></p>

    <!-- Messages flash d'erreur (solde insuffisant, etc.) -->
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire de retrait -->
    <!-- Envoie en POST vers /transaction/retrait (TransactionController::retrait) -->
    <form action="<?= base_url('transaction/retrait') ?>" method="post">

        <!-- Champ montant a retirer -->
        <label for="montant">Montant (Ar) :</label><br>
        <input
            type="number"
            name="montant"
            id="montant"
            min="1"
            placeholder="Ex: 10000"
            required
        ><br><br>

        <!-- Bouton de soumission -->
        <button type="submit">Retirer</button>

    </form>

    <!-- Information sur les frais -->
    <p><em>Des frais seront appliques selon le bareme en vigueur.</em></p>

    <hr>

    <!-- Retour au dashboard -->
    <a href="<?= base_url('compte') ?>">Retour au dashboard</a>

</body>
</html>
