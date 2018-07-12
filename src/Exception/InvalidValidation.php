<?php

declare(strict_types=1);

namespace sonrac\Auth\Exception;

use Throwable;

/**
 * Class InvalidValidation.
 */
class InvalidValidation extends \Exception
{
    protected $code    = '{object}.validate_error';
    protected $message = 'invalid validate';

    protected $errors = [];

    public function __construct(array $errors, string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set errors.
     *
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
