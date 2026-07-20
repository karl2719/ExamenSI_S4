<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table admin
// Gere les comptes administrateurs du backoffice
class AdminModel extends Model
{
    // Nom de la table
    protected $table = 'admin';
    // Cle primaire
    protected $primaryKey = 'id_admin';
    // Colonnes autorisees (ajout de id_operateur pour V2)
    protected $allowedFields = ['nom_utilisateur', 'mot_de_passe', 'id_operateur'];

    // Cherche un admin par nom d'utilisateur
    public function findByUsername($username)
    {
        // Retourne le premier admin correspondant au nom d'utilisateur
        return $this->where('nom_utilisateur', $username)->first();
    }
}
