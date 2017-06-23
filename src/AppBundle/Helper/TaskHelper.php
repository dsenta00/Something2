<?php

namespace AppBundle\Helper;

use DateTime;

/**
 * Class TaskHelper.
 *
 * @package AppBundle\Helper.
 */
class TaskHelper
{
    /**
     * Get days difference from today according to a given date.
     *
     * @param DateTime $dateTime
     * @return mixed
     */
    public static function countDaysAccordingTo(DateTime $dateTime)
    {
        $now = new DateTime("now");
        $diff = $dateTime->diff($now)->days;
        if ($dateTime < $now) {
            $diff = $diff * (-1);
        }

        return $diff;
    }
}