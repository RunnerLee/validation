<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-27 10:49
 */
use FastD\Http\ServerRequest;
use Runner\Validator\Exceptions\ValidationException;
use Runner\Validator\Validator;

if (!function_exists('validator')) {
    function validator(ServerRequest $request, array $rules)
    {
        $data = [];
        foreach ($rules as $field => $rule) {
            $field = explode('.', $field);
            $field = array_shift($field);
            if (array_key_exists($field, $data)) {
                continue;
            }
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
                $messages .= implode(';', $fieldMessages).';';
            }

            throw new ValidationException($messages, 400);
        }

        return $validator;
    }
}
