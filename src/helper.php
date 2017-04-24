<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:49
 */
use FastD\Http\ServerRequest;
use Runner\Validator\Validator;
use Runner\Validator\Exceptions\ValidationExceptions;

if (!function_exists('validator')) {
    function validator(ServerRequest $request, array $rules)
    {
        $data = [];
        foreach ($rules as $field => $rule) {
            $field = explode('.', $field);
            $field = array_shift($field);
            if (isset($request->queryParams[$field])) {
                $data[$field] = $request->queryParams[$field];
            } elseif (isset($request->bodyParams[$field])) {
                $data[$field] = $request->bodyParams[$field];
            }
        }
        $validator = new Validator($data, $rules);
        if (!$validator->validate()) {
            $messages = '';
            foreach ($validator->messages() as $fieldMessages) {
                $messages .= implode(';', $fieldMessages) . ';';
            }
            throw new ValidationExceptions($messages);
        }
        return $validator;
    }
}