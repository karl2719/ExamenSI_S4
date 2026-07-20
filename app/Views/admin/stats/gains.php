<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Situation des gains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <!-- Titre principal -->
        <h1 class="h3 mb-3">Situation des gains (frais collectes)</h1>
        <!-- Bouton retour au dashboard -->
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Dashboard</a>

        <!-- ============================================== -->
        <!-- Filtre par periode                             -->
        <!-- ============================================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <!-- Formulaire de filtre par date (methode GET pour garder les params dans l'URL) -->
                <form method="get" action="<?= site_url('admin/stats/gains') ?>" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Du :</label>
                        <!-- Champ date debut, pre-rempli si filtre actif -->
                        <input type="date" name="date_debut" class="form-control" value="<?= esc($date_debut ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Au :</label>
                        <!-- Champ date fin, pre-rempli si filtre actif -->
                        <input type="date" name="date_fin" class="form-control" value="<?= esc($date_fin ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <!-- Bouton pour appliquer le filtre -->
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- Section 1 : Gains GLOBAUX par type d'operation -->
        <!-- ============================================== -->
        <h2 class="h5">Gains globaux par type d'operation</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Type</th>
                        <th>Nombre de transactions</th>
                        <th>Total frais</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Initialise le compteur pour le total general -->
                    <?php $total_general = 0; ?>
                    <!-- Boucle sur chaque type d'operation -->
                    <?php foreach ($gains as $g): ?>
                    <tr>
                        <!-- Affiche le libelle du type (ex: Depot, Retrait, Transfert) -->
                        <td><?= esc($g['libelle']) ?></td>
                        <!-- Affiche le nombre de transactions -->
                        <td><?= $g['nb_transactions'] ?></td>
                        <!-- Affiche le total des frais formate en Ariary -->
                        <td><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                    <!-- Additionne au total general -->
                    <?php $total_general += $g['total_frais']; ?>
                    <?php endforeach; ?>
                    <!-- Ligne du total general -->
                    <tr class="table-active">
                        <td><strong>TOTAL</strong></td>
                        <td></td>
                        <td><strong><?= number_format($total_general, 0, ',', ' ') ?> Ar</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <!-- ============================================== -->
        <!-- Section 2 : Gains INTRA-operateur              -->
        <!-- (transferts entre clients du MEME operateur)   -->
        <!-- ============================================== -->
        <h2 class="h5 mt-4">Gains transferts intra-operateur <span class="badge bg-success">Meme operateur</span></h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-success">
                    <tr>
                        <th>Nombre de transferts</th>
                        <th>Total frais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Affiche le nombre de transferts intra-operateur (0 si aucun) -->
                        <td><?= $gains_intra['nb_transactions'] ?? 0 ?></td>
                        <!-- Affiche le total des frais intra-operateur -->
                        <td><?= number_format($gains_intra['total_frais'] ?? 0, 0, ',', ' ') ?> Ar</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ============================================== -->
        <!-- Section 3 : Gains INTER-operateur              -->
        <!-- (transferts vers clients d'AUTRES operateurs)  -->
        <!-- ============================================== -->
        <h2 class="h5 mt-4">Gains transferts inter-operateur <span class="badge bg-warning text-dark">Autres operateurs</span></h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-warning">
                    <tr>
                        <th>Operateur destinataire</th>
                        <th>Nombre de transferts</th>
                        <th>Total frais</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Initialise le total inter-operateur -->
                    <?php $total_inter = 0; ?>
                    <!-- Boucle sur chaque operateur destinataire -->
                    <?php foreach ($gains_inter as $gi): ?>
                    <tr>
                        <!-- Affiche le nom de l'operateur destinataire -->
                        <td><?= esc($gi['nom_operateur_dest']) ?></td>
                        <!-- Affiche le nombre de transferts vers cet operateur -->
                        <td><?= $gi['nb_transactions'] ?></td>
                        <!-- Affiche le total des frais -->
                        <td><?= number_format($gi['total_frais'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                    <!-- Additionne au total inter -->
                    <?php $total_inter += $gi['total_frais']; ?>
                    <?php endforeach; ?>
                    <!-- Si aucun transfert inter-operateur -->
                    <?php if (empty($gains_inter)): ?>
                    <tr><td colspan="3" class="text-muted">Aucun transfert inter-operateur</td></tr>
                    <?php endif; ?>
                    <!-- Ligne du total inter-operateur -->
                    <tr class="table-active">
                        <td><strong>TOTAL inter-operateur</strong></td>
                        <td></td>
                        <td><strong><?= number_format($total_inter, 0, ',', ' ') ?> Ar</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
