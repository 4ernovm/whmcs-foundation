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
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            $controller = $this->getController($request);

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
     * @return string|null
     */
    protected function getController(Request $request)
    {
        $name = $request->get("_controller");

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
        return $request->get("_action");
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
