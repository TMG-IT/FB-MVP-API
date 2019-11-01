<?php

namespace App\Monolog;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActivationStrategy extends ErrorLevelActivationStrategy
{
    protected $ignoredCodes;

    /**
     * ActivationStrategy constructor.
     *
     * @param string $actionLevel
     * @param array  $ignoredCodes
     */
    public function __construct($actionLevel, $ignoredCodes)
    {
        parent::__construct($actionLevel);

        $this->ignoredCodes = $ignoredCodes;
    }

    /**
     * @param array $record
     *
     * @return bool
     */
    public function isHandlerActivated(array $record): bool
    {
        // ignore everything under $this->actionLevel
        $isActivated = parent::isHandlerActivated($record);

        if ($isActivated && isset($record['context']['exception'])) {
            $exception = $record['context']['exception'];

            if ($exception instanceof HttpException) {
                // only log exceptions that we don't want to ignore]
                return !\in_array($exception->getStatusCode(), $this->ignoredCodes, false);
            }
        }

        return $isActivated;
    }
}
