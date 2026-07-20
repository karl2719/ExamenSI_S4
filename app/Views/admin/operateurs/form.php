<!DOCTYPE html>
<html>
<head><title>Admin - <?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> Operateur</title></head>
<body>
<h1><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> un operateur</h1>
<a href="<?= site_url('admin/operateurs') ?>">&larr; Retour</a>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<!-- Formulaire creation/edition operateur -->
<form method="post" action="<?= isset($operateur) ? site_url('admin/operateurs/update/' . $operateur['id_operateur']) : site_url('admin/operateurs/store') ?>">
    <p>
        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= esc($operateur['nom'] ?? '') ?>" required>
    </p>
    <p>
        <label>Code :</label><br>
        <input type="text" name="code" value="<?= esc($operateur['code'] ?? '') ?>" required>
    </p>
    <button type="submit"><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?></button>
</form>
</body>
</html>
