<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table admin
class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['nom_utilisateur', 'mot_de_passe'];

    // Cherche un admin par nom d'utilisateur
    public function findByUsername($username)
    {
        return $this->where('nom_utilisateur', $username)->first();
    }
}
