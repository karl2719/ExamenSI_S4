<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table baremes_frais
class BaremeFraisModel extends Model
{
    protected $table = 'baremes_frais';
    protected $primaryKey = 'id_bareme';
    protected $allowedFields = ['id_type', 'montant_min', 'montant_max', 'frais'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Recupere les baremes d'un type d'operation (tries par montant)
    public function getByType($idType)
    {
        return $this->where('id_type', $idType)
                    ->orderBy('montant_min', 'ASC')
                    ->findAll();
    }

    // Retourne le frais applicable pour un type et un montant donne
    public function getFraisApplicable($idType, $montant)
    {
        $bareme = $this->where('id_type', $idType)
                       ->where('montant_min <=', $montant)
                       ->where('montant_max >=', $montant)
                       ->first();
        return $bareme ? $bareme['frais'] : 0;
    }
}
