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
</head>
<body>

    <h1>Faire un transfert</h1>

    <!-- Affichage du solde actuel -->
    <p>Votre solde actuel : <strong><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></p>

    <!-- Messages flash d'erreur -->
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;">
            <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <!-- Formulaire de transfert -->
    <!-- Envoie en POST vers /transaction/transfert (TransactionController::transfert) -->
    <form action="<?= base_url('transaction/transfert') ?>" method="post">

        <!-- Champ numero du destinataire -->
        <label for="numero_destinataire">Numero du destinataire :</label><br>
        <input
            type="text"
            name="numero_destinataire"
            id="numero_destinataire"
            placeholder="Ex: 0337654321"
            maxlength="10"
            required
        ><br><br>

        <!-- Champ montant a transferer -->
        <label for="montant">Montant (Ar) :</label><br>
        <input
            type="number"
            name="montant"
            id="montant"
            min="1"
            placeholder="Ex: 25000"
            required
        ><br><br>

        <!-- Bouton de soumission -->
        <button type="submit">Transferer</button>

    </form>

    <!-- Information sur les frais -->
    <p><em>Des frais seront appliques selon le bareme en vigueur.</em></p>

    <hr>

    <!-- Retour au dashboard -->
    <a href="<?= base_url('compte') ?>">Retour au dashboard</a>

</body>
</html>
