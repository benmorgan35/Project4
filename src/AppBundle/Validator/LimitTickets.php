<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
* @Annotation
*/
class LimitTickets extends Constraint
{
    public $message="Nous sommes désolés. Le nombre de visiteurs maximal a été atteint pour cette journée. Merci de choisir un autre jour de visite.";

    public function validatedBy()
    {
        return 'app_limitTickets';
    }
}