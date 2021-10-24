<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class UserModel
 *
 * Represents user table from SongClicker database
 *
 * @package App\Models
 */
class UserModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'user';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'username';

    /**
     * @var string $returnType Return type
     */
    protected $returnType = 'object';

    /**
     * @var string[] $allowedFields Allowed fields
     */
    protected $allowedFields = ['username', 'password', 'type'];

}