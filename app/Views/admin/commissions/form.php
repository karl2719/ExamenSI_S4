<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre dynamique : change selon creation ou modification -->
    <title>Admin - <?= isset($commission) ? 'Modifier' : 'Ajouter' ?> Commission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <!-- Titre dynamique : "Ajouter" ou "Modifier" selon le contexte -->
        <h1 class="h3 mb-3"><?= isset($commission) ? 'Modifier' : 'Ajouter' ?> une commission inter-operateur</h1>
        <!-- Bouton retour a la liste -->
        <a href="<?= site_url('admin/commissions') ?>" class="btn btn-secondary btn-sm mb-3">&larr; Retour</a>

        <!-- Affiche le message d'erreur si present -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Formulaire de creation/modification -->
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- L'action du formulaire change selon creation ou modification -->
                <form method="post" action="<?= isset($commission) ? site_url('admin/commissions/update/' . $commission['id_commission']) : site_url('admin/commissions/store') ?>">
                    <div class="mb-3">
                        <label class="form-label">Operateur destinataire :</label>
                        <!-- Liste deroulante des operateurs (exclut notre operateur) -->
                        <select name="id_operateur_dest" class="form-select" required>
                            <option value="">-- Choisir un operateur --</option>
                            <!-- Boucle sur chaque operateur disponible -->
                            <?php foreach ($operateurs as $op): ?>
                            <!-- Pre-selectionne l'operateur si en mode modification -->
                            <option value="<?= $op['id_operateur'] ?>" <?= (isset($commission) && $commission['id_operateur_dest'] == $op['id_operateur']) ? 'selected' : '' ?>>
                                <!-- Affiche le nom et le code de l'operateur -->
                                <?= esc($op['nom']) ?> (<?= esc($op['code']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pourcentage de commission (%) :</label>
                        <!-- Champ numerique pour le pourcentage (min 0, max 100, pas de 0.01) -->
                        <input type="number" name="pourcentage" class="form-control" step="0.01" min="0" max="100" value="<?= esc($commission['pourcentage'] ?? '') ?>" required>
                    </div>
                    <!-- Bouton soumettre (texte dynamique) -->
                    <button type="submit" class="btn btn-primary"><?= isset($commission) ? 'Modifier' : 'Ajouter' ?></button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
