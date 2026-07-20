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

        <h1 class="h3 mb-3">Situation des gains (frais collectes)</h1>
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Dashboard</a>

        <!-- Filtre par periode -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="get" action="<?= site_url('admin/stats/gains') ?>" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Du :</label>
                        <input type="date" name="date_debut" class="form-control" value="<?= esc($date_debut ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Au :</label>
                        <input type="date" name="date_fin" class="form-control" value="<?= esc($date_fin ?? '') ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gains par type d'operation -->
        <h2 class="h5">Gains par type d'operation</h2>
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
                    <?php $total_general = 0; ?>
                    <?php foreach ($gains as $g): ?>
                    <tr>
                        <td><?= esc($g['libelle']) ?></td>
                        <td><?= $g['nb_transactions'] ?></td>
                        <td><?= number_format($g['total_frais'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                    <?php $total_general += $g['total_frais']; ?>
                    <?php endforeach; ?>
                    <tr class="table-active">
                        <td><strong>TOTAL</strong></td>
                        <td></td>
                        <td><strong><?= number_format($total_general, 0, ',', ' ') ?> Ar</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
