<?php


namespace App\Models;

use CodeIgniter\Model;

/**
 * Class SongModel
 *
 * Represents song table from SongClicker database
 *
 * @package App\Models
 */
class SongModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'song';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'idS';

    /**
     * @var string $returnType Return type
     */
    protected $returnType = 'object';

    /**
     * @var bool $useAutoIncrement Use auto increment
     */
    protected $useAutoIncrement = true;

    /**
     * @var bool $useSoftDeletes Use soft database
     */
    protected $useSoftDeletes = false;

    /**
     * @var string[] $allowedFields Allowed fields
     */
    protected $allowedFields = ['idS','name', 'artist', 'path', 'idP'];
}