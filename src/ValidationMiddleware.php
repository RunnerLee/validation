<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:47
 */
namespace Runner\Validation;

use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Psr\Http\Message\ServerRequestInterface;
use Runner\Validator\Exceptions\ValidationExceptions;
use FastD\Http\Response;
use FastD\Http\JsonResponse;

class ValidationMiddleware extends Middleware
{
    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        try {
            if ($validator = config()->get('validation.' . route()->getActiveRoute()->getName(), false)) {
                $reflection = new \ReflectionClass($validator);
                if ($reflection->isSubclassOf(RequestValidatorInterface::class)) {
                    throw new \Exception(sprintf('%s is not instance of %s', $reflection->getName(), RequestValidatorInterface::class));
                }
                validator($request, $reflection->newInstance()->rules());
            }

            return $next($request);
        } catch (ValidationExceptions $exception) {
            return new JsonResponse([
                'msg' => $exception->getMessage(),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
