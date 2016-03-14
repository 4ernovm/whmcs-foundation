<?php

use Chernoff\Foundation\Kernel;
use Chernoff\Container\ContainerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

if (!function_exists("response")) {
    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response($content, $status = Response::HTTP_OK, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }
}

if (!function_exists("response_plain")) {
    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response_plain($content, $status = Response::HTTP_OK, array $headers = [])
    {
        // We have to do a little trick and do not allow WHMCS to sent all it's content.
        $response = new Response($content, $status, $headers);
        $response->sendHeaders();

        die($response->getContent());
    }
}

if (!function_exists("response_json")) {
    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response_json($content, $status = Response::HTTP_OK, array $headers = [])
    {
        // We have to do a little trick and do not allow WHMCS to sent all it's content.
        $response = new JsonResponse($content, $status, $headers);
        $response->sendHeaders();

        die($response->getContent());
    }
}

if (!function_exists("response_redirect")) {
    /**
     * @param string $path
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response_redirect($path, $status = Response::HTTP_FOUND, array $headers = [])
    {
        return new RedirectResponse($path, $status, $headers);
    }
}

if (!function_exists("generate_url")) {
    /**
     * @param $addon_name
     * @param $controller
     * @param $action
     * @param array $params
     * @return string
     */
    function generate_url($addon_name, $controller, $action, array $params = [])
    {
        $params = array_filter($params, function ($item) { return !is_null($item); });
        $params["module"]      = $addon_name;
        $params["_controller"] = $controller;
        $params["_action"]     = $action;

        return "?" . http_build_query($params);
    }
}

if (!function_exists("handle_request")) {
    /**
     * Allows to handle requests to custom addon pages using controllers, DI and other good stuff.
     *
     * @param string $controller
     *  Default controller to use in case if it hasn't been set yet
     * @param string $action
     *  Default action (e.g. controller method)
     * @param array $vars
     *  Params passed into addon's _output function
     */
    function handle_request($controller, $action, array $vars)
    {
        $container = ContainerFactory::getContainer();
        $request   = Request::createFromGlobals();

        if (!$request->query->has("_controller")) $request->query->set("_controller", $controller);
        if (!$request->query->has("_action")) $request->query->set("_action", $action);

        /** @var Kernel $kernel */
        $kernel    = $container->make("kernel");
        $response  = $kernel->handle($request);

        $response->sendHeaders();
        echo $response->getContent();
    }
}
