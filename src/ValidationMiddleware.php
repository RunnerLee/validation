<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:47
 */

namespace Runner\Validation;

use FastD\Http\JsonResponse;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Runner\Validator\Exceptions\ValidationException;

class ValidationMiddleware extends Middleware
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $next
     *
     * @return JsonResponse|\Psr\Http\Message\ResponseInterface
     *
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            return $next->process($request);
        } catch (ValidationException $exception) {
            return call_user_func(
                config()->get('exception.response'),
                $exception
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
