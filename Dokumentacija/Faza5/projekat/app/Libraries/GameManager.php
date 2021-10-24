<?php
namespace App\Libraries;

use App\Models\SongModel;
use App\Models\UserPlaylistModel;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\UserInfoModel;

/**
 * Class GameManager - A class that implements all the WebSocket Server methods
 * @package App\Libraries
 *
 * @version 1.0
 */
class GameManager implements MessageComponentInterface {

    /**
     * @var \SplObjectStorage $clients Clients
     */
    protected $clients;

    /**
     * @var array $users Users
     */
    protected $users;

    /**
     * @var array $activeGames ActiveGames
     */
    protected $activeGames;

    /**
     * @var int $currentGameId CurrentGameID
     */
    protected $currentGameId = 0;

    /**
     * GameManager constructor. Initializes client storage to be used for all incoming connections.
     */
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * A method that makes an intersection between playlists both players have in the selected genre
     * and searches the database for all the songs in the intersected playlists then returns them.
     *
     * @param $player1Playlists
     * @param $player2Playlists
     * @return array
     */
    public function formBothHaveSongs($player1Playlists, $player2Playlists): array
    {
        $idPs1 = [];
        $idPs2 = [];

        foreach($player1Playlists as $playlist)
            $idPs1 []= $playlist->idP;

        foreach($player2Playlists as $playlist)
            $idPs2 []= $playlist->idP;

        $bothHavePlaylists = array_intersect($idPs1, $idPs2);

        $songModel = new SongModel();

        $songsBothHave = [];
        foreach($bothHavePlaylists as $playlist)
            $songsBothHave = array_merge($songsBothHave, $songModel->where("idP", $playlist)->findAll());

        return $songsBothHave;
    }

    /**
     * A method that returns two arrays of songs.
     * First array is filled with the songs which the first player has in the selected genre
     * while second contains songs which the second player has.
     *
     * @param $player1Playlists
     * @param $player2Playlists
     * @return array[]
     */
    public function formOneHaveSongs($player1Playlists, $player2Playlists): array
    {
        $idPs1 = [];
        $idPs2 = [];

        foreach($player1Playlists as $playlist)
            $idPs1 []= $playlist->idP;

        foreach($player2Playlists as $playlist)
            $idPs2 []= $playlist->idP;

        $firstHavePlaylists = array_diff($idPs1, $idPs2);
        $secondHavePlaylists = array_diff($idPs2, $idPs1);

        $firstHaveSongs = [];
        $secondHaveSongs = [];

        $songModel = new SongModel();

        foreach($firstHavePlaylists as $playlist)
            $firstHaveSongs = array_merge($firstHaveSongs, $songModel->where("idP", $playlist)->findAll());

        foreach($secondHavePlaylists as $playlist)
            $secondHaveSongs = array_merge($secondHaveSongs, $songModel->where("idP", $playlist)->findAll());

        return [0 => $firstHaveSongs, 1 => $secondHaveSongs];
    }

    /**
     * A method that is called every time a client makes a connection.
     * It tries to match the client with another one waiting for a player in the same genre.
     * If found, makes a new gameId to represent a game session between two players and sends them
     * a message to start the game.
     *
     * @param ConnectionInterface $conn
     */
    public function match(ConnectionInterface $conn) {
        $opponent = null;
        foreach($this->clients as $client) {
            if ($this->users[$client->resourceId]['status'] == 'inQueue' &&
                $conn != $client &&
                $this->users[$conn->resourceId]['chosenGenre'] == $this->users[$client->resourceId]['chosenGenre'])
            {
                $opponent = $client;
                break;
            }
        }
        if ($opponent != null) {
            $this->users[$conn->resourceId]['status'] = 'playing';
            $this->users[$opponent->resourceId]['status'] = 'playing';

            $userInfoModel = new UserInfoModel();

            $userInfo1 = $userInfoModel->where("username", $this->users[$conn->resourceId]['username'])
                                      ->where("genre", $this->users[$conn->resourceId]['chosenGenre'])
                                      ->first();

            $userInfo2 = $userInfoModel->where("username", $this->users[$opponent->resourceId]['username'])
                                       ->where("genre", $this->users[$opponent->resourceId]['chosenGenre'])
                                       ->first();

            $userPlaylistModel = new UserPlaylistModel();

            $player1Playlists = $userPlaylistModel->where("idU", $userInfo1->idU)->findAll();
            $player2Playlists = $userPlaylistModel->where("idU", $userInfo2->idU)->findAll();

            $this->activeGames[$this->currentGameId]['bothHaveSongs'] = $this->formBothHaveSongs($player1Playlists, $player2Playlists);
            $this->activeGames[$this->currentGameId]['oneHaveSongs'] = $this->formOneHaveSongs($player1Playlists, $player2Playlists);
            $this->activeGames[$this->currentGameId]['player1'] = $conn;
            $this->activeGames[$this->currentGameId]['player2'] = $opponent;
            $this->activeGames[$this->currentGameId]['currentRound'] = 0;
            $this->activeGames[$this->currentGameId]['endOfRound'] = 0;
            $pickedSongs = $this->pickSongs($this->currentGameId);
            $conn->send($this->users[$opponent->resourceId]['username'] . "|" . $this->currentGameId . "|" . json_encode($pickedSongs) . "|" . $this->activeGames[$this->currentGameId]['songOrArtist']);
            $opponent->send($this->users[$conn->resourceId]['username'] . "|" . $this->currentGameId . "|" . json_encode($pickedSongs) . "|" . $this->activeGames[$this->currentGameId]['songOrArtist']);
            $this->currentGameId++;
        }
    }

    /**
     * A method called upon every start of new round between two players.
     * Picks a song to be played in the upcoming round in 60:40 proportion
     * between songs that both players have and songs that only one has.
     * Then it picks three more songs to be shown to the players for guessing and returns them.
     *
     * @param $gameId
     * @return array
     */
    public function pickSongs($gameId): array
    {
        $data = [];
        $this->activeGames[$gameId]['songOrArtist'] = rand(0, 100);
        $whichToPick = rand(0, 100);
        if (($whichToPick <= 60 || count($this->activeGames[$gameId]['oneHaveSongs'][0]) == 0 && count($this->activeGames[$gameId]['oneHaveSongs'][1]) == 0)
        && count($this->activeGames[$gameId]['bothHaveSongs']) > 0) {
            $songToPlayIndex = rand(0, count($this->activeGames[$gameId]['bothHaveSongs']) - 1);
            $data['songToBePlayed'] = $this->activeGames[$gameId]['bothHaveSongs'][$songToPlayIndex];
            array_splice($this->activeGames[$gameId]['bothHaveSongs'], $songToPlayIndex, 1);
        }
        else {
            $fromWhichPlayer = rand(0, 100);
            if (($fromWhichPlayer <= 50 || count($this->activeGames[$gameId]['oneHaveSongs'][1]) == 0)
                    && count($this->activeGames[$gameId]['oneHaveSongs'][0]) > 0) {
                $songToPlayIndex = rand(0, count($this->activeGames[$gameId]['oneHaveSongs'][0]) - 1);
                $data['songToBePlayed'] = $this->activeGames[$gameId]['oneHaveSongs'][0][$songToPlayIndex];
                array_splice($this->activeGames[$gameId]['oneHaveSongs'][0], $songToPlayIndex, 1);
            }
            else if (count($this->activeGames[$gameId]['oneHaveSongs'][1]) > 0){
                $songToPlayIndex = rand(0, count($this->activeGames[$gameId]['oneHaveSongs'][1]) - 1);
                $data['songToBePlayed'] = $this->activeGames[$gameId]['oneHaveSongs'][1][$songToPlayIndex];
                array_splice($this->activeGames[$gameId]['oneHaveSongs'][1], $songToPlayIndex, 1);
            }
        }
        $data['songs'] []= $this->activeGames[$gameId]['songOrArtist'] >= 50 ? $data['songToBePlayed']->name : $data['songToBePlayed']->artist;
        $allSongs = array_merge($this->activeGames[$gameId]['bothHaveSongs'], $this->activeGames[$gameId]['oneHaveSongs'][0], $this->activeGames[$gameId]['oneHaveSongs'][1]);

        $used = [];
        $i = 0;
        while ($i < 3) {
            $currentSongNumber = rand(0, count($allSongs) - 1);
            if (in_array($currentSongNumber, $used) ||
                ($this->activeGames[$gameId]['songOrArtist'] >= 50 && $allSongs[$currentSongNumber]->name == $data['songToBePlayed']->name) ||
                ($this->activeGames[$gameId]['songOrArtist'] < 50 && $allSongs[$currentSongNumber]->artist == $data['songToBePlayed']->artist))
                continue;
            $data['songs'] []= $this->activeGames[$gameId]['songOrArtist'] >= 50 ? $allSongs[$currentSongNumber]->name : $allSongs[$currentSongNumber]->artist;
            $used []= $currentSongNumber;
            $i++;
        }
        shuffle($data['songs']);
        $data['randomTime'] = (double)rand(0, 100) / 100;
        return $data;
    }

    /**
     * A method that is called every time a connection is made by the client.
     * Saves all the relevant client information including his username and chosen genre when starting a multiplayer game.
     * Then calls a method in which the client tries to connect to another client waiting for the match in the same genre.
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring,$queryarray);
        $userInfoModel = new UserInfoModel();
        $userInfo = $userInfoModel->where('username', $queryarray['username'])->where('genre', $queryarray['chosenGenre'])->findAll();
        $this->users[$conn->resourceId] = ['username' => $queryarray['username'], 'chosenGenre' => $queryarray['chosenGenre'], 'points' => $userInfo[0]->points, 'status' => 'inQueue'];
        $this->match($conn);
    }

    /**
     * A method that is called every time a client passes a message to the server.
     * It splits the message in the correct format,
     * and based on the content makes an interaction with opponent in the current game session.
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $info = explode("|", $msg);
        $gameId = intval($info[1]);
        switch ($info[0]) {
            case "answered": {
                if ($this->activeGames[$gameId]['player1'] == $from) {
                    $this->activeGames[$gameId]['answer1'] = [$info[4], $info[2]];
                    $this->activeGames[$gameId]['player2']->send("answered|" . $info[2] . "|" . $info[3]);
                }
                else {
                    $this->activeGames[$gameId]['answer2'] = [$info[4], $info[2]];
                    $this->activeGames[$gameId]['player1']->send("answered|" . $info[2] . "|" . $info[3]);
                }
                break;
            }
            case "sendPoints": {
                if (!isset($this->activeGames[$gameId]['points1'])) {
                    $points1 = $points2 = 0;
                    if (isset($this->activeGames[$gameId]['answer1']) && isset($this->activeGames[$gameId]['answer2'])) {
                        if ($this->activeGames[$gameId]['answer1'][0] == 1 && $this->activeGames[$gameId]['answer2'][0] == 1)
                        {
                            if ($this->activeGames[$gameId]['answer1'][1] == $this->activeGames[$gameId]['answer2'][1]) {
                                $points1 = $points2 = 4;
                            }
                            else if ($this->activeGames[$gameId]['answer1'][1] < $this->activeGames[$gameId]['answer2'][1]) {
                                $points1 = 4;
                                $points2 = 2;
                            }
                            else {
                                $points1 = 2;
                                $points2 = 4;
                            }
                        }
                        else if ($this->activeGames[$gameId]['answer1'][0] == 1 && $this->activeGames[$gameId]['answer2'][0] == 0) {
                            $points1 = 4;
                            $points2 = -1;
                        }
                        else if ($this->activeGames[$gameId]['answer1'][0] == 0 && $this->activeGames[$gameId]['answer2'][0] == 1) {
                            $points1 = -1;
                            $points2 = 4;
                        }
                        else {
                            $points1 = $points2 = -1;
                        }
                    }
                    else if (isset($this->activeGames[$gameId]['answer1'])) {
                        if ($this->activeGames[$gameId]['answer1'][0] == 1)
                            $points1 = 4;
                        else
                            $points1 = -1;
                    }
                    else if (isset($this->activeGames[$gameId]['answer2'])) {
                        if ($this->activeGames[$gameId]['answer2'][0] == 1)
                            $points2 = 4;
                        else
                            $points2 = -1;
                    }

                    $this->activeGames[$gameId]['points1'] = $points1;
                    $this->activeGames[$gameId]['points2'] = $points2;
                }
                if ($this->activeGames[$gameId]['player1'] == $from)
                    $from->send("points|" . $this->activeGames[$gameId]['points1'] . "|" . $this->activeGames[$gameId]['points2']);
                else
                    $from->send("points|" . $this->activeGames[$gameId]['points2'] . "|" . $this->activeGames[$gameId]['points1']);
                break;
            }
            case "endOfRound": {
                $this->activeGames[$gameId]['endOfRound']++;
                if ($this->activeGames[$gameId]['endOfRound'] == 2) {
                    $this->activeGames[$gameId]['endOfRound'] = 0;
                    $this->activeGames[$gameId]['currentRound']++;
                    $pickedSongs = $this->pickSongs($gameId);
                    unset($this->activeGames[$gameId]['answer1']);
                    unset($this->activeGames[$gameId]['answer2']);
                    unset($this->activeGames[$gameId]['points1']);
                    unset($this->activeGames[$gameId]['points2']);
                    $this->activeGames[$gameId]['player1']->send("numOfSongs|" . json_encode($this->activeGames[$gameId]['oneHaveSongs'][0]));
                    $this->activeGames[$gameId]['player1']->send("newRound|" . json_encode($pickedSongs) . "|" . $this->activeGames[$gameId]['songOrArtist']);
                    $this->activeGames[$gameId]['player2']->send("newRound|" . json_encode($pickedSongs) . "|" . $this->activeGames[$gameId]['songOrArtist']);
                }
                break;
            }
        }
    }

    /**
     * A method that is called every time a client closes the connection to the server.
     * If a player didn't leave the game "gracefully" (force quits), the server
     * automatically closes the connection with opponent in the same session.
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        foreach ($this->activeGames as $activeGame) {
            if (($activeGame['player1'] == $conn || $activeGame['player2'] == $conn) &&
                $activeGame['currentRound'] >= 9) {
                $gameToDelete = &$activeGame;
                break;
            }
            if ($activeGame['player1'] == $conn) {
                $activeGame['player2']->send("playerLeft");
                $gameToDelete = &$activeGame;
                break;
            }
            else if ($activeGame['player2'] == $conn) {
                $activeGame['player1']->send("playerLeft");
                $gameToDelete = &$activeGame;
                break;
            }
        }

        if (isset($gameToDelete))
            unset($gameToDelete);

        unset($this->users[$conn->resourceId]);

        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}