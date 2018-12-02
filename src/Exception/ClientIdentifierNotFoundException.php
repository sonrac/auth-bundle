<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/2/18
 * Time: 3:50 PM
 */

declare(strict_types=1);


namespace sonrac\Auth\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class ClientIdentifierNotFoundException
 * @package sonrac\Auth\Exception
 */
class ClientIdentifierNotFoundException extends AuthenticationException
{
    /**
     * @var string
     */
    private $clientIdentifier;

    /**
     * ClientIdentifierNotFoundException constructor.
     * @param string $clientIdentifier
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $clientIdentifier, string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->clientIdentifier = $clientIdentifier;
    }

    /**
     * @return string
     */
    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }
}
