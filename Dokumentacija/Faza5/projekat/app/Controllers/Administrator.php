<?php


namespace App\Controllers;


use App\Models\ChangeLogModel;
use App\Models\MistakeLogModel;
use App\Models\UserInfoModel;
use App\Models\UserModel;
use App\Models\UserPlaylistModel;

/**
 * Class Administrator - Represents all the functionalities that an administrator has
 * @package App\Controllers
 *
 * @version 1.0
 */
class Administrator extends PrivilegedUser
{

    /**
     * A method to show main administrator page
     */
    public function index(){
        $this->showView("adminMenu");
    }

    /**
     * An optional method which a class can implement if additional data is required by the showView method
     * @return array
     */
    protected function showAdditionalData()
    {
        return ['welcomeMessage' => "Welcome, {$this->session->get('username')} <br> <div style='color: purple'>Administrator</div>"];
    }

    /**
     * A method that deletes all user info associated with the username provided from the page
     */
    public function deleteAccount(){
        $userModel = new UserModel();
        $toDelete =$userModel->find($this->request->getVar('accountToDelete'));
        if($toDelete!=null) {
            $userInfoModel = new UserInfoModel();
            $userPlaylistModel = new UserPlaylistModel();
            $toDeleteInfo = $userInfoModel->where('username', $toDelete->username)->findAll();

            foreach ($toDeleteInfo as $userInfo) {
                $userPlaylists = $userPlaylistModel->where("idU", $userInfo->idU)->findAll();
                foreach($userPlaylists as $playlist){
                    $userPlaylistModel->where("idUP", $playlist->idUP)->delete();
                }
                $userInfoModel->where("idU", $userInfo->idU)->delete();
            }

            $userModel->where("username", $toDelete->username)->delete();
            echo "";
        }
        else
            echo "Invalid username";
    }

    /**
     * Checks if a username already exists in the database.
     *
     * @throws \ReflectionException
     */
    public function checkModerator() {
        $users = new UserModel();
        $username = $this->request->getVar("modUsername");


        $taken = $users->find($username);

        if($taken != null){
            echo "User with that username already exists";
        }
        echo "";
    }

    public function saveNewModerator(){
        $users = new UserModel();
        $users->insert([
            'username' =>  $this->request->getVar("modUsername"),
            'password' => $this->request->getVar("modPassword"),
            'type' => "mod"
        ]);
    }

    /**
     * Returns all the information about moderator changes from the database.
     */
    public function getChangeLog() {
        $changeLogModel=new ChangeLogModel();
        $logs=$changeLogModel->findAll();
        foreach ($logs as $log){
            echo $log->username.",". $log->operation.",".$log->dateTime."/";
        }
    }

    public function deleteMistake(){
        $idS = $this->request->getVar("idSong");
        $mistakeLogModel = new MistakeLogModel();

        $mistakeLogModel->where("idS", $idS)->delete();
    }
}