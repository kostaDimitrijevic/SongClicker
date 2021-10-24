<?php


namespace App\Models;
use CodeIgniter\Model;

/**
 * Class UserInfoModel
 *
 * Represents user_info table from SongClicker database
 *
 * @package App\Models
 */
class UserInfoModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table      = 'user_info';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'id';

    /**
     * @var string $returnType Return type
     */
    protected $returnType = 'object';

    /**
     * @var bool $useAutoIncrement Use auto increment
     */
    protected $useAutoIncrement = true;

    /**
     * @var string[] $allowedFields Allowed fields
     */
    protected $allowedFields = ['id','username', 'genre', 'points', 'tokens'];



}