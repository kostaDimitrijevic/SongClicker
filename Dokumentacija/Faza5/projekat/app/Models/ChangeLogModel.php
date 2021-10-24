<?php


namespace App\Models;


use CodeIgniter\Model;

/**
 * Class ChangeLogModel
 *
 * Represent change_log table from SongClicker database
 *
 * @package App\Models
 */
class ChangeLogModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'change_log';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'idC';

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
    protected $allowedFields = ['idC', 'operation', 'username', 'dateTime'];

}