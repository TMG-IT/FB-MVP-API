<?php

namespace App\Service;

use App\Exception\SessionNotFoundException;
use App\Repository\SessionRepository;
use App\Entity\Session;
use App\Exception\ExpiredSessionCodeException;
use App\Exception\InvalidSessionCodeException;

class SessionService
{
    /** @var SessionRepository */
    private $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * Validates given session code and returns it
     *
     * NetSuite proposed response sample (not confirmed yet):
     *
     * {
     *      "session":
     *          {
     *              "id": "f36110d5-c3b1-44ee-bca4-03ae4cd68194",
     *              "title": "My Title",
     *              "coach": {
     *              "forename": "David",
     *              "surname":  "O'shea"
     *              },
     *              "configuration": {
     *              "collectContactData": true
     *              }
     *           }
     *      }
     *  }
     *
     *
     *
     * @param string $sessionCode
     *
     * @return Session
     *
     * @throws ExpiredSessionCodeException
     */
    public function validate(string $sessionCode): Session
    {
        /**
         * @todo
         *
         * We will first fetch the session from NetSuite.
         *
         * If session is returned, it is valid and unexpired, and it is not found in local database,
         * we wll need to create it, together with questions
         *
         * If session is returned, it is valid and unexpired, and it is found in local database,
         * we will just update the status for it (valid/invalid/expired)
         */
        /** @var Session $session */
        $session = $this->sessionRepository->findOneBySessionCode($sessionCode);

        if ($session && $session->getStatus() === Session::SESSION_STATUS_VALID) {
            return $session;
        }

        /*
         * If session is not found on NetSuite and local database, throw 404
         */
        if (!$session) {
            throw new SessionNotFoundException();
        }

        /*
         * If session is found, but status is invalid, throw invalid session exception
         */
        if ($session && $session->getStatus() === Session::SESSION_STATUS_INVALID) {
            throw new InvalidSessionCodeException();
        }

        /*
         * If session is found, but status is expired, throw expired session exception
         */
        if ($session && $session->getStatus() === Session::SESSION_STATUS_EXPIRED) {
            throw new ExpiredSessionCodeException($session->getSessionName());
        }
    }
}
