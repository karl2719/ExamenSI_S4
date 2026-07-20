<!-- ==========================================================
     Vue : Historique des transactions
     
     Affiche un tableau avec toutes les transactions du client :
     - Transactions emises (depots, retraits, transferts envoyes)
     - Transactions recues (transferts recus)
     
     Les transactions sont triees par date decroissante
     (plus recentes en premier).
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Mobile Money</title>
</head>
<body>

    <h1>Historique des transactions</h1>

    <!-- Verification si le client a des transactions -->
    <?php if (empty($transactions)): ?>

        <!-- Aucune transaction -->
        <p>Aucune transaction pour le moment.</p>

    <?php else: ?>

        <!-- Tableau des transactions -->
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Sens</th>
                    <th>Montant (Ar)</th>
                    <th>Frais (Ar)</th>
                    <th>Total (Ar)</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <!-- Date de la transaction -->
                    <td><?= esc($t['date_transaction']) ?></td>

                    <!-- Type d'operation (Depot, Retrait, Transfert) -->
                    <td><?= esc($t['type_libelle']) ?></td>

                    <!-- Sens de la transaction :
                         - "Envoye" si le client est l'emetteur
                         - "Recu" si le client est le destinataire (transfert recu) -->
                    <td>
                        <?php if ($t['type_code'] === 'TRANSFERT' && (int)$t['id_client_destinataire'] === (int)$id_client): ?>
                            Recu
                        <?php elseif ($t['type_code'] === 'DEPOT'): ?>
                            Depot
                        <?php else: ?>
                            Envoye
                        <?php endif; ?>
                    </td>

                    <!-- Montant de l'operation -->
                    <td><?= number_format($t['montant'], 0, ',', ' ') ?></td>

                    <!-- Frais appliques -->
                    <td><?= number_format($t['frais'], 0, ',', ' ') ?></td>

                    <!-- Montant total (montant + frais) -->
                    <td><?= number_format($t['montant_total'], 0, ',', ' ') ?></td>

                    <!-- Statut de la transaction -->
                    <td><?= esc($t['statut']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <hr>

    <!-- Retour au dashboard -->
    <a href="<?= base_url('compte') ?>">Retour au dashboard</a>

</body>
</html>
