<?php

namespace Digbang\Backoffice\Exceptions;

/**
 * @deprecated Validation is handled with Laravel's exception classes.
 */
class ValidationException extends \RuntimeException
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param array           $errors
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($errors, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
