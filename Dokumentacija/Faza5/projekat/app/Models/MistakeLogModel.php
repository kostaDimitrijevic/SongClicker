<?php


namespace App\Models;


use CodeIgniter\Model;

/**
 * Class MistakeLogModel
 *
 * Represent mistake_log table from SongClicker database
 *
 * @package App\Models
 */
class MistakeLogModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'mistake_log';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'idM';

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
    protected $allowedFields = ['idM', 'idS'];

}