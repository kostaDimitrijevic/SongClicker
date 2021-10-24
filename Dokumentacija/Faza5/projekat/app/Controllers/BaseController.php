<?php

namespace App\Controllers;

use App\Models\SongModel;
use App\Models\UserInfoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var IncomingRequest|CLIRequest
	 */
	protected $request;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['form', 'url'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		$this->session = \Config\Services::session();
	}

    /**
     * A method that every controller implements.
     * When called, returns a pattern filled with a page provided.
     * Additionally, adds data provided by the second argument.
     * If showAdditionalData() method is implemented in the derived class, the method calls it and inserts data
     * to the page, if not, does nothing.
     *
     * @param $page
     * @param array $data
     */
    public function showView($page, array $data = []) {
	    $rawData = $this->showAdditionalData();
	    foreach($rawData as $key => $value)
            $data[$key] = $value;
        $data['middlePart'] = view("pages/$page", $data);
        echo view("patterns/default_page_pattern", $data);
    }

    /**
     * A method that every derived controller should implement if
     * a page it is showing requires additional data.
     *
     * @return array
     */
    protected function showAdditionalData() {
	    return [];
    }

    /**
     * A lightweight method that is called using ajax requests.
     * Works similarly to showView method, but second argument
     * is a method name if additional data is required on provided page.
     *
     * @param $page
     * @param null $additionalData
     */
    public function echoView($page, $additionalData = null)
    {
        if ($additionalData != null)
            $data = $this->{$additionalData}();
        if(isset($data))
            echo view("pages/$page", $data);
        else echo view("pages/$page");
    }

    /**
     * A method that destroys user's current session and redirects him to the Login page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url("Login?wantToExit=true"));
    }

    /**
     * A method that returns a song information based on it's id provided by the request.
     */
    public function getSongInfo()
    {
        $songModel = new SongModel();
        $id = $this->request->getVar("idS");
        $song = $songModel->find($id);
        $songString = $song->idS . "," . $song->name . "," . $song->artist . "," . $song->path;
        echo $songString;
    }

    public function getGenrePoints() {
        $userInfoModel=new UserInfoModel();

        if($this->request->getVar("genre") == "allGenres"){
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
}
