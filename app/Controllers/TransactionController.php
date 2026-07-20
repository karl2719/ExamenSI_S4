<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\BaremeFraisModel;

/**
 * TransactionController — Gestion des operations client
 *
 * Ce controleur gere les 3 types d'operations :
 * - Depot   : ajout d'argent sur le compte (automatique, sans frais)
 * - Retrait : retrait d'argent du compte (automatique, avec frais selon bareme)
 * - Transfert : envoi d'argent vers un autre client (avec frais selon bareme)
 *
 * Il gere aussi l'affichage de l'historique des transactions.
 *
 * Pour le calcul des frais, il utilise BaremeFraisModel::getFraisApplicable()
 * qui cherche la tranche de frais correspondant au montant de l'operation.
 *
 * Routes associees :
 *   GET/POST /transaction/depot      -> depot()
 *   GET/POST /transaction/retrait    -> retrait()
 *   GET/POST /transaction/transfert  -> transfert()
 *   GET      /transaction/historique -> historique()
 */
class TransactionController extends BaseController
{
    // -------------------------------------------------------
    // DEPOT
    // -------------------------------------------------------

    /**
     * Gere le depot d'argent sur le compte du client
     *
     * GET  : affiche le formulaire de depot
     * POST : traite le depot
     *
     * Le depot est automatique (pas de validation manuelle) et sans frais.
     */
    public function depot()
    {
        // --- Traitement du formulaire (POST) ---
        if ($this->request->getMethod() === 'POST') {
            $montant = (float) $this->request->getPost('montant');

            // Validation : le montant doit etre positif
            if ($montant <= 0) {
                return redirect()->back()
                                 ->with('error', 'Le montant doit etre superieur a 0');
            }

            // Recuperer l'ID du client connecte
            $idClient = session()->get('id_client');

            // Inserer le depot en base de donnees
            $transactionModel = new TransactionModel();
            $result = $transactionModel->insererDepot($idClient, $montant);

            if ($result) {
                // Succes : rediriger vers le dashboard avec un message
                return redirect()->to('/compte')
                                 ->with('success', 'Depot de ' . number_format($montant, 0, ',', ' ') . ' Ar effectue avec succes');
            } else {
                return redirect()->back()
                                 ->with('error', 'Erreur lors du depot');
            }
        }

        // --- Affichage du formulaire (GET) ---
        return view('client/depot');
    }

    // -------------------------------------------------------
    // RETRAIT
    // -------------------------------------------------------

    /**
     * Gere le retrait d'argent du compte du client
     *
     * GET  : affiche le formulaire de retrait (avec le solde actuel)
     * POST : traite le retrait
     *
     * Le retrait est automatique. Des frais sont appliques selon le bareme
     * defini dans la table baremes_frais (via BaremeFraisModel::getFraisApplicable).
     * Le solde du client doit etre suffisant pour couvrir montant + frais.
     */
    public function retrait()
    {
        $clientModel = new ClientModel();
        $idClient = session()->get('id_client');

        // --- Traitement du formulaire (POST) ---
        if ($this->request->getMethod() === 'POST') {
            $montant = (float) $this->request->getPost('montant');

            // Validation : le montant doit etre positif
            if ($montant <= 0) {
                return redirect()->back()
                                 ->with('error', 'Le montant doit etre superieur a 0');
            }

            // Calculer le solde actuel du client
            $solde = $clientModel->getSolde($idClient);

            // Recuperer l'ID du type d'operation RETRAIT
            $db = \Config\Database::connect();
            $type = $db->table('types_operation')
                       ->where('code', 'RETRAIT')
                       ->get()
                       ->getRowArray();

            // Calculer les frais applicables avec le bareme
            $baremeFraisModel = new BaremeFraisModel();
            $frais = $baremeFraisModel->getFraisApplicable($type['id_type'], $montant);

            // Verifier que le solde est suffisant (montant + frais)
            if ($solde < ($montant + $frais)) {
                return redirect()->back()
                                 ->with('error', 'Solde insuffisant. Votre solde : '
                                    . number_format($solde, 0, ',', ' ') . ' Ar. '
                                    . 'Montant + frais : '
                                    . number_format($montant + $frais, 0, ',', ' ') . ' Ar');
            }

            // Inserer le retrait en base de donnees
            $transactionModel = new TransactionModel();
            $result = $transactionModel->insererRetrait($idClient, $montant, $frais);

            if ($result) {
                return redirect()->to('/compte')
                                 ->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ')
                                    . ' Ar effectue (frais : ' . number_format($frais, 0, ',', ' ') . ' Ar)');
            } else {
                return redirect()->back()
                                 ->with('error', 'Erreur lors du retrait');
            }
        }

        // --- Affichage du formulaire (GET) ---
        // On passe le solde actuel pour l'afficher dans le formulaire
        $solde = $clientModel->getSolde($idClient);

        return view('client/retrait', [
            'solde' => $solde,
        ]);
    }

    // -------------------------------------------------------
    // TRANSFERT
    // -------------------------------------------------------

    /**
     * Gere le transfert d'argent vers un autre client
     *
     * GET  : affiche le formulaire de transfert
     * POST : traite le transfert
     *
     * Le transfert necessite :
     * - Le numero du destinataire (cree automatiquement si inexistant)
     * - Le montant a envoyer
     * Les frais sont calcules selon le bareme (BaremeFraisModel::getFraisApplicable)
     * L'emetteur paie montant + frais, le destinataire recoit le montant.
     */
    public function transfert()
    {
        $clientModel = new ClientModel();
        $idClient = session()->get('id_client');

        // --- Traitement du formulaire (POST) ---
        if ($this->request->getMethod() === 'POST') {
            $montant = (float) $this->request->getPost('montant');
            $numeroDestinataire = $this->request->getPost('numero_destinataire');

            // Validation du montant
            if ($montant <= 0) {
                return redirect()->back()
                                 ->with('error', 'Le montant doit etre superieur a 0');
            }

            // Validation du numero destinataire
            if (empty($numeroDestinataire) || strlen($numeroDestinataire) < 10) {
                return redirect()->back()
                                 ->with('error', 'Numero du destinataire invalide (minimum 10 chiffres)');
            }

            // Verifier que le client ne s'envoie pas a lui-meme
            $emetteur = $clientModel->find($idClient);
            if ($emetteur['numero_telephone'] === $numeroDestinataire) {
                return redirect()->back()
                                 ->with('error', 'Vous ne pouvez pas vous transferer a vous-meme');
            }

            // Verifier le prefixe du destinataire
            $prefixe = substr($numeroDestinataire, 0, 3);
            $db = \Config\Database::connect();
            $prefixeExiste = $db->table('prefixes')
                                ->where('prefixe', $prefixe)
                                ->get()
                                ->getRowArray();

            if (!$prefixeExiste) {
                return redirect()->back()
                                 ->with('error', 'Le prefixe du destinataire n\'est pas reconnu');
            }

            // Chercher le destinataire, ou le creer automatiquement
            $destinataire = $clientModel->findByTelephone($numeroDestinataire);
            if (!$destinataire) {
                // Creer automatiquement le compte du destinataire
                $idDestinataire = $clientModel->creerAutomatique($numeroDestinataire);
                if (!$idDestinataire) {
                    return redirect()->back()
                                     ->with('error', 'Erreur lors de la creation du compte destinataire');
                }
            } else {
                $idDestinataire = $destinataire['id_client'];
            }

            // Recuperer l'ID du type d'operation TRANSFERT
            $type = $db->table('types_operation')
                       ->where('code', 'TRANSFERT')
                       ->get()
                       ->getRowArray();

            // Calculer les frais applicables avec le bareme
            $baremeFraisModel = new BaremeFraisModel();
            $frais = $baremeFraisModel->getFraisApplicable($type['id_type'], $montant);

            // Verifier le solde de l'emetteur (montant + frais)
            $solde = $clientModel->getSolde($idClient);
            if ($solde < ($montant + $frais)) {
                return redirect()->back()
                                 ->with('error', 'Solde insuffisant. Votre solde : '
                                    . number_format($solde, 0, ',', ' ') . ' Ar. '
                                    . 'Montant + frais : '
                                    . number_format($montant + $frais, 0, ',', ' ') . ' Ar');
            }

            // Effectuer le transfert
            $transactionModel = new TransactionModel();
            $result = $transactionModel->insererTransfert($idClient, $idDestinataire, $montant, $frais);

            if ($result) {
                return redirect()->to('/compte')
                                 ->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ')
                                    . ' Ar vers ' . $numeroDestinataire
                                    . ' effectue (frais : ' . number_format($frais, 0, ',', ' ') . ' Ar)');
            } else {
                return redirect()->back()
                                 ->with('error', 'Erreur lors du transfert');
            }
        }

        // --- Affichage du formulaire (GET) ---
        $solde = $clientModel->getSolde($idClient);

        return view('client/transfert', [
            'solde' => $solde,
        ]);
    }

    // -------------------------------------------------------
    // HISTORIQUE
    // -------------------------------------------------------

    /**
     * Affiche l'historique complet des transactions du client
     *
     * Inclut toutes les transactions ou le client est emetteur ou destinataire.
     * Les transactions sont triees par date decroissante (plus recentes d'abord).
     */
    public function historique()
    {
        $transactionModel = new TransactionModel();
        $idClient = session()->get('id_client');

        // Recuperer toutes les transactions du client
        $transactions = $transactionModel->getHistorique($idClient);

        // Afficher la vue historique
        return view('client/historique', [
            'transactions' => $transactions,
            'id_client'    => $idClient, // Pour identifier les transferts envoyes/recus
        ]);
    }
}
