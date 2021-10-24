<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use App\Models\SongModel;
use App\Models\UserInfoModel;
use App\Models\UserPlaylistModel;

/**
 * Class Training - A class that handles all the training mode logic
 * @package App\Controllers
 */
class Training extends BaseController
{
    /**
     * A method that depending on the mode does next:
     * If the mode is set to "train", the method finds songs from all available playlists that a user currently has for the selected genre.
     * If the mode is set to "unlock", the method returns all the songs from the selected genre.
     */
    public function getSongsFromPlaylists() {
        $songModel = new SongModel();
        $userInfoModel = new UserInfoModel();
        $mode = $this->session->get("mode");
        if ($mode == "train") {
            $userInfo = $userInfoModel->where("username", $this->session->get("username"))->where("genre", $this->session->get("chosenGenre"))->first();
            $userPlaylistModel = new UserPlaylistModel();
            $userPlaylists = $userPlaylistModel->where("idU", $userInfo->idU)->findAll();
            $idPs = [];

            foreach($userPlaylists as $playlist)
                $idPs = array_merge($idPs, [$playlist->idP]);
        }
        else {
            $playlistModel = new PlaylistModel();
            $playlistsForGenre = $playlistModel->where("genre", $this->session->get("chosenGenre"))->findAll();
            $idPs = [];

            foreach($playlistsForGenre as $playlist)
                $idPs = array_merge($idPs, [$playlist->idP]);
        }
        $this->songs = $songModel->whereIn("idP", $idPs)->findAll();
        $this->session->set("songs", $this->songs);
    }

    /**
     * A method that initially calls the method to pick the songs, then shows the training mode page to the player.
     */
    public function index()
    {
        $this->getSongsFromPlaylists();
        $this->showView('trainingMode');
    }

    /**
     * A method that randomly picks a song to be played next round, and names of three others to be shown to the player
     *
     * @param false $toJson
     * @return array|false|string
     */
    public function pickSongs(bool $toJson = false) {
        $data = [];
        $used = [];
        $i = 0;
        $this->songs = $this->session->get("songs");
        $songToPlayIndex = rand(0, count($this->songs) - 1);
        $data['songToBePlayed'] = $this->songs[$songToPlayIndex];
        $data['songs'] []= $this->songs[$songToPlayIndex]->name;
        while ($i < 3) {
            $currentSongNumber = rand(0, count($this->songs) - 1);
            if (in_array($currentSongNumber, $used) || $this->songs[$currentSongNumber]->name == $data['songToBePlayed']->name)
                continue;
            $data['songs'] []= $this->songs[$currentSongNumber]->name;
            $used []= $currentSongNumber;
            $i++;
        }
        shuffle($data['songs']);
        array_splice($this->songs, $songToPlayIndex, 1);
        $this->session->set("songs", $this->songs);
        if ($toJson == false)
            return $data;
        else
            return json_encode($data);
    }

    /**
     * Finds the current user info from the database and adds newly acquired tokens from the training
     *
     * @throws \ReflectionException
     */
    public function saveUserTokens() {
        $userInfoModel = new UserInfoModel();
        $userInfo = $userInfoModel
                                ->where("username", $this->session->get("username"))
                                ->where("genre", $this->session->get("chosenGenre"))
                                ->first();
        $userInfoModel
                ->where("username", $this->session->get("username"))
                ->where("genre", $this->session->get("chosenGenre"))
                ->update(null, ["tokens" => $userInfo->tokens + $this->request->getVar("tokens")]);
    }

    /**
     * If a genre was unlocked during the "unlock" mode, inserts new user info of the genre into the database.
     * Also inserts a first easy playlist of the genre for the current user.
     *
     * @throws \ReflectionException
     */
    public function saveNewUserInfo() {
        $userInfoModel = new UserInfoModel();
        $userInfoModel->insert([
            "username" => $this->session->get("username"),
            "genre" => $this->session->get("chosenGenre"),
            "points" => 0,
            "tokens" => 0
        ]);
        $userInfo = $userInfoModel->where("username", $this->session->get("username"))->where("genre", $this->session->get("chosenGenre"))->first();
        $userPlaylistModel = new UserPlaylistModel();
        $playlistModel = new PlaylistModel();
        $userPlaylistModel->insert([
            'idU' => $userInfo->idU,
            'idP' => $playlistModel->getIdOfMinNumOfGenre($this->session->get("chosenGenre"))
        ]);
        $this->session->remove("mode");
    }
}
