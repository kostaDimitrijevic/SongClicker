<?php

namespace App\Controllers;

use App\Models\MistakeLogModel;
use App\Models\PlaylistModel;
use App\Models\UserInfoModel;
use App\Models\GenreModel;
use App\Models\UserPlaylistModel;

/**
 * Class User - For handling all user actions.
 * @package App\Controllers
 *
 * @version 1.0
 */
class User extends BaseController
{

    /**
     * A method to show user menu page
     */
    public function index()
    {
        $this->showView('userInterface', []);
    }

    /**
     * An optional method which a class can implement if additional data is required by the showView method
     * @return array
     */
    protected function showAdditionalData() {
        return ['welcomeMessage' => "Welcome,<br> <b>{$this->session->get('username')}</b>"];
    }

    /**
     * Sets current chosen genre in training or multiplayer game
     * @return array
     */
    public function setChosenGenre() {
        $this->session->set("chosenGenre", $this->request->getVar("chosenGenre"));
        return [];
    }

    /**
     * Redirects user to Training controller
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function goToTraining() {
        $this->session->set("chosenGenre", $this->request->getVar("chosenGenre"));
        $this->session->set("mode", $this->request->getVar("mode"));
        return redirect()->to(base_url("Training"));
    }

    /**
     * Returns all available genres for user upon requesting to play multiplayer game
     * @return array
     */
    public function selectAvailableGenresForUser()
    {
        $userInfoModel = new UserInfoModel();
        $userInfo = $userInfoModel->where('username', $this->session->get('username'))->findAll();
        return ['userInfo' => $userInfo];
    }

    /**
     * Echoes all available genres for user upon requesting to view genres and playlists
     */
    public function getMyGenres() {
        $userInfoModel=new UserInfoModel();
        $infos=$userInfoModel->where('username',$this->session->get('username'))->findAll();
        foreach ($infos as $info){
            echo $info->genre . ",";
        }
    }

    /**
     * Returns points and tokens of the current player for all genres, or for the specific genre.
     */
    public function getPointsAndTokens() {
        $userInfoModel=new UserInfoModel();
        if($this->request->getVar("genre")=="allGenres"){
            $infos = $userInfoModel->where('username', $this->session->get('username'))->findAll();
            $points=0;
            $tokens=0;
            foreach ($infos as $info){
                $points+=$info->points;
                $tokens+=$info->tokens;
            }
            echo $points . "," . $tokens;
        }
        else {
            $infos = $userInfoModel->where('username', $this->session->get('username'))->
            where('genre', $this->request->getVar("genre"))->findAll();
            echo $infos[0]->points . "," . $infos[0]->tokens;
        }
    }

    /**
     * Returns usernames and points of all players for the specific genre, or for all genres.
     */
    public function getGenrePoints() {
        $userInfoModel=new UserInfoModel();
        if($this->request->getVar("genre")=="allGenres"){
            $arr=[];
            $infos=$userInfoModel->findAll();
            foreach($infos as $info){
                if(array_key_exists($info->username, $arr))
                    $arr[$info->username]+=$info->points;
                else
                    $arr[$info->username]=$info->points;
            }
            foreach($arr as $key => $value)
                echo $key . "/" . $value . ",";
        }
        else {
            $infos=$userInfoModel->where('genre', $this->request->getVar("genre"))->findAll();
            foreach ($infos as $info)
                echo $info->username."/".$info->points.",";
        }
        echo $this->session->get('username');
    }


    /**
     * Updates current number of tokens with newly acquired ones from the game
     * @throws \ReflectionException
     */
    public function savePointsAndTokens() {
        $userInfoModel = new UserInfoModel();
        $userInfo = $userInfoModel
                                ->where("username", $this->session->get("username"))
                                ->where("genre", $this->session->get("chosenGenre"))
                                ->first();

        $userInfoModel
                    ->where("username", $this->session->get("username"))
                    ->where("genre", $this->session->get("chosenGenre"))
                    ->update(null, [
                                "tokens" => ($userInfo->tokens + intval($this->request->getVar("tokens")))
                            ]);
        $userInfoModel
                    ->where("username", $this->session->get("username"))
                    ->where("genre", $this->session->get("chosenGenre"))
                    ->update(null, [
                        "points" => ($userInfo->points + intval($this->request->getVar("points")))
                    ]);
    }

    /**
     * Returns the map of all genres and information if it is locked or unlocked by the user
     * @return array[]
     */
    public function getGenres() {
        $genreModel = new GenreModel();
        $userInfo = new UserInfoModel();
        $user = $this->session->get("username");

        $infos = $userInfo->where("username", $user)->findAll();
        $genres = $genreModel->findAll();

        $toSend = [];

        foreach ($genres as $genre){
            $flag = false;
            foreach($infos as $info){
                if($info->genre == $genre->name){
                    $flag = true;
                    break;
                }
            }
            if($flag == true){
                $toSend[$genre->name] = "unlocked";
            }
            else{
                $toSend[$genre->name] = "locked";
            }
        }

        return ["genres" => $toSend];
    }

    /**
     * Echoes all playlists for chosen genre and information about it's locked status
     */
    public function getPlaylists(){

        $genre = $this->request->getVar("chosenGenre");
        $playlistModel = new PlaylistModel();
        $userPlaylistModel = new UserPlaylistModel();
        $userInfoModel = new UserInfoModel();

        $userInfo = $userInfoModel->where("genre", $genre)->where("username", $this->session->get("username"))->findAll();

        $playlists = $playlistModel->where("genre", $genre)->findAll();
        $userPlaylists = $userPlaylistModel->where("idU", $userInfo[0]->idU)->findAll();

        foreach($playlists as $playlist){
            $flag = false;
            foreach ($userPlaylists as $userPlaylist){
                if($playlist->idP == $userPlaylist->idP){
                    $flag = true;
                    break;
                }
            }
            if($flag == true) {
               echo   $playlist->difficulty . "," . $playlist->number . "," . $playlist->price . "," . $playlist->idP . "," . "unlocked" . "/";
            }
            else {
                echo  $playlist->difficulty . "," . $playlist->number . "," . $playlist->price . "," . $playlist->idP . "," . "locked" . "/";
            }
        }
    }

    /**
     * Echoes tokens for current user
     */
    public function getMyTokens(){
        $userInfoModel = new UserInfoModel();
        $genre = $this->request->getVar("chosenGenre");

        $info = $userInfoModel->where("genre", $genre)->where("username", $this->session->get("username"))->findAll();

        echo $info[0]->tokens;
    }

    /**
     * Echoes playlist for provided id
     */
    public function getPlaylistById(){
        $playlistModel = new PlaylistModel();
        $id = $this->request->getVar("idP");

        $playlist = $playlistModel->find($id);

        echo ucfirst($playlist->genre) . " " . ucfirst($playlist->difficulty) . " " . $playlist->number . "/" . $playlist->price;
    }

    /**
     * Inserts newly bought playlist into the database
     * @throws \ReflectionException
     */
    public function buyPlaylist(){
        $genre = $this->request->getVar("genre");
        $id = $this->request->getVar("idP");

        $userInfoModel = new UserInfoModel();
        $playlistModel = new PlaylistModel();

        $playlist = $playlistModel->find($id);

        $info = $userInfoModel->where("genre", $genre)->where("username", $this->session->get("username"))->findAll();
        $tokens = $info[0]->tokens;
        $userInfoModel->where("idU", $info[0]->idU)->update(null, ["tokens" => $tokens - $playlist->price]);

        $userPlaylistModel = new UserPlaylistModel();
        $userPlaylistModel->insert([
            "idU" => $info[0]->idU,
            "idP" => $id
        ]);
    }

    /**
     * Inserts the id of the song that was reported by the user from the game
     * @throws \ReflectionException
     */
    public function reportMistake(){
        $mistakeLogModel = new MistakeLogModel();

        $mistakeLogModel->insert(["idS" => $this->request->getVar("idSong")]);
    }
}