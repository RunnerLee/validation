<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:45
 */
namespace Runner\Validation;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class ValidationServerProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->get('dispatcher')->withAddMiddleware(new ValidationMiddleware());
    }
}
