<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Montants a envoyer par operateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <!-- Titre principal -->
        <h1 class="h3 mb-3">Montants a envoyer par operateur</h1>
        <!-- Bouton retour au dashboard -->
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Dashboard</a>

        <!-- ============================================== -->
        <!-- Filtre par periode                             -->
        <!-- ============================================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <!-- Formulaire de filtre par date -->
                <form method="get" action="<?= site_url('admin/stats/montants-operateurs') ?>" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Du :</label>
                        <!-- Champ date debut -->
                        <input type="date" name="date_debut" class="form-control" value="<?= esc($date_debut ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Au :</label>
                        <!-- Champ date fin -->
                        <input type="date" name="date_fin" class="form-control" value="<?= esc($date_fin ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <!-- Bouton filtrer -->
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- Tableau recapitulatif par operateur             -->
        <!-- ============================================== -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h2 class="h5 mb-0">Recapitulatif des montants a reverser</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Operateur</th>
                                <th>Code</th>
                                <th>Nb transferts</th>
                                <th>Total montants</th>
                                <th>Commission (%)</th>
                                <th>Montant commission</th>
                                <th>Montant net a reverser</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Initialise les totaux -->
                            <?php $total_montant = 0; $total_commission = 0; $total_net = 0; ?>
                            <!-- Boucle sur chaque operateur externe -->
                            <?php foreach ($resultats as $r): ?>
                            <tr>
                                <!-- Nom de l'operateur destinataire -->
                                <td><?= esc($r['nom_operateur']) ?></td>
                                <!-- Code de l'operateur -->
                                <td><?= esc($r['code_operateur']) ?></td>
                                <!-- Nombre de transferts vers cet operateur -->
                                <td><?= $r['nb_transferts'] ?></td>
                                <!-- Total des montants transferes -->
                                <td><?= number_format($r['total_montant'], 0, ',', ' ') ?> Ar</td>
                                <!-- Pourcentage de commission configure -->
                                <td><?= $r['pourcentage'] ?> %</td>
                                <!-- Montant de la commission prelevee -->
                                <td><?= number_format($r['montant_commission'], 0, ',', ' ') ?> Ar</td>
                                <!-- Montant net a envoyer a l'operateur -->
                                <td><strong><?= number_format($r['montant_net'], 0, ',', ' ') ?> Ar</strong></td>
                            </tr>
                            <!-- Cumule les totaux -->
                            <?php $total_montant += $r['total_montant']; ?>
                            <?php $total_commission += $r['montant_commission']; ?>
                            <?php $total_net += $r['montant_net']; ?>
                            <?php endforeach; ?>
                            <!-- Si aucun transfert inter-operateur -->
                            <?php if (empty($resultats)): ?>
                            <tr><td colspan="7" class="text-muted">Aucun transfert inter-operateur</td></tr>
                            <?php endif; ?>
                            <!-- Ligne des totaux -->
                            <tr class="table-active">
                                <td colspan="3"><strong>TOTAL</strong></td>
                                <!-- Total des montants -->
                                <td><strong><?= number_format($total_montant, 0, ',', ' ') ?> Ar</strong></td>
                                <td></td>
                                <!-- Total des commissions -->
                                <td><strong><?= number_format($total_commission, 0, ',', ' ') ?> Ar</strong></td>
                                <!-- Total net a reverser -->
                                <td><strong><?= number_format($total_net, 0, ',', ' ') ?> Ar</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
