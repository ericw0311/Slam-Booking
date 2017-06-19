<?php
// src/SD/CoreBundle/Validator/TimetableLineOrder.php

namespace SD\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TimetableLineOrder extends Constraint
{
    // public $message = 'Voici la variable2.1 "{{ string1 }}" et ID2 "{{ string2 }} et ID3 "{{ string3 }}"';
    public $message = 'timetableLine.order.control';
    
    public function getTargets()
    {
    return self::CLASS_CONSTRAINT;
    }
    
    public function validatedBy()
    {
    return 'sd_core_timetableLineOrder';
    }
}