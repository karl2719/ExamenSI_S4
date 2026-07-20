<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table prefixes
class PrefixeModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id_prefixe';
    protected $allowedFields = ['prefixe', 'id_operateur'];

    // Recupere les prefixes d'un operateur
    public function getByOperateur($idOperateur)
    {
        return $this->where('id_operateur', $idOperateur)->findAll();
    }
}
