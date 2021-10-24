<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Class LoginFilter - A filter class to stop the users from accessing another user type controller or login page
 *
 * @package App\Filters
 *
 * @version 1.0
 */
class LoginFilter implements FilterInterface
{
    /**
     * A method that checks if the request is being forwarded to the controller which
     * does not correspond with the actual user type logged into the system.
     *
     * @param RequestInterface $request
     * @param null $arguments
     * @return \CodeIgniter\HTTP\RedirectResponse|mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->has("type"))
            return redirect()->to(base_url("Login"));

        $requestTo = $request->getUri()->getSegment(1);
        if ($session->get("type") == 'user' && $requestTo != 'User')
            return redirect()->to(base_url("User"));
        if ($session->get("type") == 'mod' && $requestTo != 'Moderator')
            return redirect()->to(base_url("Moderator"));
        else if ($session->get("type") == 'admin' && $requestTo != 'Administrator')
            return redirect()->to(base_url("Administrator"));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}