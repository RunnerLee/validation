<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-05
 */

namespace Runner\Validation;

interface RequestValidatorInterface
{
    /**
     * @return array
     */
    public function rules() : array;
}
