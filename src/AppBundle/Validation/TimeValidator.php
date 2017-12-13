<?php

namespace AppBundle\Validation;

use DateTime;

class TimeValidator
{
    public function isOneDayOld(\DateTime $dateAdded)
    {
        $dateLimit = new DateTime();
        $dateLimit->modify("-12 hours");
        if ($dateAdded < $dateLimit) {
            return false;
        }
        return true;
    }
}