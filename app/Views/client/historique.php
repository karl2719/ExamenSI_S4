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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-4">Historique des transactions</h1>

        <!-- Verification si le client a des transactions -->
        <?php if (empty($transactions)): ?>

            <!-- Aucune transaction -->
            <div class="alert alert-info">Aucune transaction pour le moment.</div>

        <?php else: ?>

            <!-- Tableau des transactions -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
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
                                    <span class="badge bg-success">Recu</span>
                                <?php elseif ($t['type_code'] === 'DEPOT'): ?>
                                    <span class="badge bg-info">Depot</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Envoye</span>
                                <?php endif; ?>
                            </td>

                            <!-- Montant de l'operation -->
                            <td><?= number_format($t['montant'], 0, ',', ' ') ?></td>

                            <!-- Frais appliques -->
                            <td><?= number_format($t['frais'], 0, ',', ' ') ?></td>

                            <!-- Montant total (montant + frais) -->
                            <td><?= number_format($t['montant_total'], 0, ',', ' ') ?></td>

                            <!-- Statut de la transaction -->
                            <td>
                                <span class="badge <?= $t['statut'] === 'reussi' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= esc($t['statut']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

        <hr>

        <!-- Retour au dashboard -->
        <a href="<?= base_url('compte') ?>" class="btn btn-secondary">&larr; Retour au dashboard</a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
