<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table commissions_inter_operateur
// Gere les commissions appliquees aux transferts entre operateurs differents
class CommissionModel extends Model
{
    // Nom de la table en base
    protected $table = 'commissions_inter_operateur';
    // Cle primaire
    protected $primaryKey = 'id_commission';
    // Colonnes autorisees pour insert/update
    protected $allowedFields = ['id_operateur_source', 'id_operateur_dest', 'pourcentage'];
    // Activer la gestion automatique des timestamps
    protected $useTimestamps = true;
    // Nom de la colonne created_at
    protected $createdField = 'created_at';
    // Nom de la colonne updated_at
    protected $updatedField = 'updated_at';

    /**
     * Recupere toutes les commissions d'un operateur source
     * avec le nom de l'operateur destinataire
     */
    public function getBySource(int $idOperateur): array
    {
        // Connexion a la base
        $db = \Config\Database::connect();
        // Requete : jointure avec operateurs pour avoir le nom du destinataire
        return $db->table('commissions_inter_operateur c')
            ->select('c.*, o.nom as nom_operateur_dest, o.code as code_operateur_dest')
            ->join('operateurs o', 'o.id_operateur = c.id_operateur_dest')
            ->where('c.id_operateur_source', $idOperateur)
            ->get()
            ->getResultArray();
    }

    /**
     * Recupere le pourcentage de commission entre 2 operateurs
     * Retourne 0 si aucune commission configuree
     */
    public function getCommission(int $idSource, int $idDest): float
    {
        // Cherche la commission pour ce couple source/destination
        $result = $this->where('id_operateur_source', $idSource)
                       ->where('id_operateur_dest', $idDest)
                       ->first();
        // Retourne le pourcentage trouve, ou 0 par defaut
        return $result ? (float) $result['pourcentage'] : 0.0;
    }
}
