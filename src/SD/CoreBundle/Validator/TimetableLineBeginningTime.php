<?php
// src/SD/CoreBundle/Validator/TimetableLineBeginningTime.php

namespace SD\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TimetableLineBeginningTime extends Constraint
{
    public $message = 'timetableLine.order.beginningTime.control';
    
    public function getTargets()
    {
    return self::CLASS_CONSTRAINT;
    }
    
    public function validatedBy()
    {
    return 'sd_core_timetableLineBeginningTime';
    }
}
