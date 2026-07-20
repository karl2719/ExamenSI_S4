<?php
namespace App\Models;
use CodeIgniter\Model;

// Model pour la table types_operation
class TypeOperationModel extends Model
{
    protected $table = 'types_operation';
    protected $primaryKey = 'id_type';
    protected $allowedFields = ['code', 'libelle'];
}
