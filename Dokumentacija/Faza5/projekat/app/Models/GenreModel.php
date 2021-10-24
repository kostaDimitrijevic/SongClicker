<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Class GenreModel
 *
 * Represents genre table from SongClicker database
 *
 * @package App\Models
 */
class GenreModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table = 'genre';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primary= 'name';

    /**
     * @var string $returnType Return type
     */
    protected $returnType='object';

    /**
     * @var string[] $allowedFields Allowed fields
     */
    protected $allowedFields = ['name'];
}
