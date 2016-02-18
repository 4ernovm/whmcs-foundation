<?php

namespace Chernoff\Foundation;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Kernel
 * @package Chernoff\Foundation
 */
class Kernel
{
    /** @var Container */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param $defaultController
     * @return Response
     */
    public function handle(Request $request, $defaultController)
    {
        try {
            $controller = $this->getController($request, $defaultController);

            if (!$controller) {
                throw new \RuntimeException('Unable to find the controller.');
            }

            $action = $this->getAction($request);

            if (!$action) {
                throw new \RuntimeException('Unable to find the action.');
            }

            $response = $controller->{$action}($request);

            if (!($response instanceof Response)) {
                throw new \LogicException('The controller must return a response.');
            }

            return $response;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     * @param $default
     * @return string|null
     */
    protected function getController(Request $request, $default)
    {
        $name = $request->get("_controller", $default);

        if (!$name) {
            return null;
        }

        return $this->container->make($name);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getAction(Request $request)
    {
        return $request->get("_action", "index");
    }

    /**
     * @param $e
     * @return Response
     */
    protected function handleException($e)
    {
        $this->container->make("exception_handler")->handle($e);

        return new Response($e->getMessage());
    }
}
