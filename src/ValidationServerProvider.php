<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:45
 */

namespace Runner\Validation;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Runner\Validator\Validator;

class ValidationServerProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        $container->get('dispatcher')->withAddMiddleware(new ValidationMiddleware());

        Validator::addExtension('exists', function ($field, $value, array $parameters = []) {
            // [{connection}.]{database},field[,conditionField1,conditionValue2]
            $dsn = array_shift($parameters);
            $mainKey = 0 === count($parameters) ? 'id' : array_shift($parameters);
            false === strpos($dsn, '.') && ($dsn = 'default.'.$dsn);
            list($connection, $table) = explode('.', $dsn);
            $condition = [];
            $parameters = array_chunk($parameters, 2);
            foreach ($parameters as $item) {
                if (2 !== count($item)) {
                    break;
                }
                $condition[$item[0]] = $item[1];
            }

            $condition[$mainKey] = $value;

            ksort($condition);

            $cacheKeyName = app()->getName().".validate_exist.{$connection}.{$table}." . md5(http_build_query($condition));

            $item = cache()->getItem($cacheKeyName);

            if (!$item->isHit()) {
                $item->set(
                    eloquent_db($connection)->table($table)->where($condition)->exists()
                );
                $item->expiresAfter(300);
                cache()->save($item);
            }

            return $item->get();
        });
    }
}
