<?php
namespace Noxxie\Mailtopay\Exceptions;

use Exception;
use Illuminate\Contracts\Support\MessageBag;

class InvalidParameterException extends Exception {

    /**
     * Contains the message bag instance
     *
     * @var \Illuminate\Contracts\Support\MessageBag|null
     */
    protected $messageBag;
    
    /**
     * Constructor method.
     *
     * @param string $message
     * @param integer $code
     * @param MessageBag|null $messageBag
     */
    public function __construct(string $message = '', $code = 0, ?MessageBag $messageBag = null)
    {
        $this->messageBag = $messageBag;
        parent::__construct($message, $code);
    }

    /**
     * Get the specific errors for this exception.
     *
     * @return \Illuminate\Contracts\Support\MessageBag|null
     */
    public function getMessageBag() : ?MessageBag
    {
        return $this->messageBag;
    }

    /**
     * Returns if the given exception has a errorbag.
     *
     * @return boolean
     */
    public function hasMessageBag() : bool
    {
        return !is_null($this->messageBag);
    }
}