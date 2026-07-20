<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commissions inter-operateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <!-- Titre de la page -->
        <h1 class="h3 mb-3">Commissions inter-operateur</h1>
        <!-- Bouton retour au dashboard -->
        <a href="<?= site_url('admin') ?>" class="btn btn-secondary btn-sm">&larr; Dashboard</a>
        <!-- Bouton pour ajouter une nouvelle commission -->
        <a href="<?= site_url('admin/commissions/create') ?>" class="btn btn-primary btn-sm">+ Ajouter une commission</a>

        <!-- Affiche le message de succes si present -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <!-- Tableau des commissions -->
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Operateur destinataire</th>
                        <th>Code</th>
                        <th>Commission (%)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucle sur chaque commission configuree -->
                    <?php foreach ($commissions as $c): ?>
                    <tr>
                        <!-- Affiche l'ID de la commission -->
                        <td><?= $c['id_commission'] ?></td>
                        <!-- Affiche le nom de l'operateur destinataire (echappe pour securite) -->
                        <td><?= esc($c['nom_operateur_dest']) ?></td>
                        <!-- Affiche le code de l'operateur destinataire -->
                        <td><?= esc($c['code_operateur_dest']) ?></td>
                        <!-- Affiche le pourcentage de commission -->
                        <td><?= $c['pourcentage'] ?> %</td>
                        <td>
                            <!-- Lien pour modifier cette commission -->
                            <a href="<?= site_url('admin/commissions/edit/' . $c['id_commission']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <!-- Lien pour supprimer (avec confirmation JavaScript) -->
                            <a href="<?= site_url('admin/commissions/delete/' . $c['id_commission']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette commission ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Si aucune commission n'est configuree -->
                    <?php if (empty($commissions)): ?>
                    <tr><td colspan="5" class="text-muted">Aucune commission configuree</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
