<?php

namespace App\Controllers;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Libraries\GameManager;

/**
 * Class WebSocketServer - A wrapper class for starting the WebSocket Server
 * @package App\Controllers
 *
 * @version 1.0
 */
class WebSocketServer extends BaseController
{
    /**
     * A method that should be in all cases run from the CLI.
     * Starts the WebSocket Server on port 8081
     */
    public function index()
    {
        if(!is_cli())
            die("Please run the server from the cli.");

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new GameManager()
                )
            ),
            8081
        );

        $server->run();
    }
}
