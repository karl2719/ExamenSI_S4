<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table operateurs
class OperateurModel extends Model
{
    protected $table = 'operateurs';
    protected $primaryKey = 'id_operateur';
    protected $allowedFields = ['nom', 'code'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
