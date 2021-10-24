<?php


namespace App\Models;

use CodeIgniter\Model;

/**
 * Class PlaylistModel
 *
 * Represents playlist table from SongClicker database
 *
 * @package App\Models
 */
class PlaylistModel extends Model
{
    /**
     * @var string $table Table
     */
    protected $table      = 'playlist';

    /**
     * @var string $primaryKey Primary key
     */
    protected $primaryKey = 'idP';

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
    protected $allowedFields = ['idP','difficulty', 'genre', 'number', 'price'];

    /**
     * Gets all playlists for the provided genre then it returns an id
     * of the playlist that has minimal number
     * @param $genre
     * @return mixed
     */
    public function getIdOfMinNumOfGenre($genre) {

        $playlists = $this->where("genre", $genre)->findAll();
        $idP = $playlists[0]->idP;
        $minNum = $playlists[0]->number;
        foreach($playlists as $playlist) {
            if ($playlist->number < $minNum) {
                $idP = $playlist->idP;
                $minNum = $playlist->number;
            }
        }

        return $idP;
    }
}