<?php
namespace App\Models;

use CodeIgniter\Model;

/**
 * Class UserPlaylistModel
 *
 * Represents user_playlist table from SongClicker database
 *
 * @package App\Models
 */
class UserPlaylistModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'user_playlist';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'idUP';

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
    protected $allowedFields = ['idUP', 'idU', 'idP'];

}
