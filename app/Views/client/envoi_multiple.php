<!-- ==========================================================
     Vue : Formulaire d'envoi multiple

     Permet au client d'envoyer de l'argent a plusieurs numeros
     en meme temps. Le montant total est divise equitablement
     entre tous les destinataires.
     ========================================================== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoi Multiple - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-4">

        <h1 class="h3 mb-3">Envoi multiple</h1>

        <!-- Affichage du solde actuel -->
        <p>Votre solde actuel : <strong class="text-primary"><?= number_format($solde, 0, ',', ' ') ?> Ar</strong></p>

        <!-- Messages flash d'erreur -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'envoi multiple -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?= base_url('transaction/envoi-multiple') ?>" method="post" id="formEnvoiMultiple">

                    <!-- Champ montant total -->
                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant total a envoyer (Ar) :</label>
                        <input
                            type="number"
                            name="montant"
                            id="montant"
                            class="form-control"
                            min="1"
                            placeholder="Ex: 100000"
                            required
                        >
                    </div>

                    <!-- Section des numeros destinataires -->
                    <div class="mb-3">
                        <label class="form-label">Numeros des destinataires :</label>
                        <div id="numerosContainer">
                            <!-- Les 2 premiers champs de numero (minimum requis) -->
                            <div class="input-group mb-2 numero-row">
                                <span class="input-group-text">1</span>
                                <input
                                    type="text"
                                    name="numeros[]"
                                    class="form-control numero-input"
                                    placeholder="Ex: 0337654321"
                                    maxlength="10"
                                    required
                                >
                            </div>
                            <div class="input-group mb-2 numero-row">
                                <span class="input-group-text">2</span>
                                <input
                                    type="text"
                                    name="numeros[]"
                                    class="form-control numero-input"
                                    placeholder="Ex: 0321234567"
                                    maxlength="10"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Bouton pour ajouter un numero -->
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnAjouterNumero">
                            + Ajouter un numero
                        </button>
                    </div>

                    <!-- Resume de la repartition (dynamique) -->
                    <div class="alert alert-info" id="resumeRepartition" style="display: none;">
                        <strong>Repartition :</strong>
                        <span id="montantParDest">0</span> Ar par destinataire
                        (<span id="nbDestinataires">0</span> destinataire(s))
                    </div>

                    <!-- Option : inclure les frais de retrait -->
                    <div class="form-check mb-3">
                        <input
                            type="checkbox"
                            name="inclure_frais_retrait"
                            id="inclure_frais_retrait"
                            class="form-check-input"
                            value="1"
                        >
                        <label for="inclure_frais_retrait" class="form-check-label">
                            Inclure les frais de retrait
                        </label>
                        <div class="form-text text-muted">
                            <small>Si coche, vous payez aussi les frais de retrait pour chaque destinataire.</small>
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary">Envoyer</button>

                </form>
            </div>
        </div>

        <!-- Information sur les frais -->
        <p class="text-muted mt-3"><em>Des frais de transfert seront calcules individuellement pour chaque destinataire selon le bareme en vigueur.</em></p>

        <hr>

        <!-- Retour au dashboard -->
        <a href="<?= base_url('compte') ?>" class="btn btn-secondary">&larr; Retour au dashboard</a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // -------------------------------------------------------
        // Logique JavaScript pour l'ajout/suppression dynamique
        // de numeros et le calcul en temps reel de la repartition
        // -------------------------------------------------------

        let compteurNumero = 2; // On commence avec 2 numeros

        /**
         * Met a jour le resume de repartition en temps reel
         */
        function mettreAJourResume() {
            const montant = parseFloat(document.getElementById('montant').value) || 0;
            const nbNumeros = document.querySelectorAll('.numero-input').length;
            const resume = document.getElementById('resumeRepartition');
            const montantParDest = document.getElementById('montantParDest');
            const nbDest = document.getElementById('nbDestinataires');

            if (montant > 0 && nbNumeros > 0) {
                resume.style.display = 'block';
                const parDest = Math.floor(montant / nbNumeros);
                // Formater le montant avec des espaces comme separateur de milliers
                montantParDest.textContent = parDest.toLocaleString('fr-FR');
                nbDest.textContent = nbNumeros;
            } else {
                resume.style.display = 'none';
            }
        }

        /**
         * Met a jour les numeros d'ordre affiches dans les champs
         */
        function mettreAJourNumerotation() {
            const rows = document.querySelectorAll('.numero-row');
            rows.forEach(function (row, index) {
                row.querySelector('.input-group-text').textContent = index + 1;
            });
            compteurNumero = rows.length;
        }

        // Bouton "Ajouter un numero"
        document.getElementById('btnAjouterNumero').addEventListener('click', function () {
            compteurNumero++;
            const container = document.getElementById('numerosContainer');

            const div = document.createElement('div');
            div.className = 'input-group mb-2 numero-row';
            div.innerHTML =
                '<span class="input-group-text">' + compteurNumero + '</span>' +
                '<input type="text" name="numeros[]" class="form-control numero-input" ' +
                'placeholder="Ex: 034xxxxxxx" maxlength="10" required>' +
                '<button type="button" class="btn btn-outline-danger btn-retirer">&times;</button>';

            container.appendChild(div);

            // Ecouter le changement sur le nouveau champ
            div.querySelector('.numero-input').addEventListener('input', mettreAJourResume);

            // Bouton de suppression
            div.querySelector('.btn-retirer').addEventListener('click', function () {
                div.remove();
                mettreAJourNumerotation();
                mettreAJourResume();
            });

            mettreAJourResume();
        });

        // Ecouter les changements sur le montant
        document.getElementById('montant').addEventListener('input', mettreAJourResume);

        // Ecouter les changements sur les numeros existants
        document.querySelectorAll('.numero-input').forEach(function (input) {
            input.addEventListener('input', mettreAJourResume);
        });
    </script>

</body>
</html>
