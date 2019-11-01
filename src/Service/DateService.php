<?php

namespace App\Service;

class DateService
{
    /**
     * Generates and returns UTC DateTime object
     *
     * @return \DateTime
     */
    public static function generateUTCDate(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
