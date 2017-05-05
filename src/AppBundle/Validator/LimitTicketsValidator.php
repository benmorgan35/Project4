<?php

namespace AppleBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

Class LimitTicketsPerDayValidator extends ConstraintValidator
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }

    public function validate($value, Constraint $constraint)
    {
        $ticketsNumber = $this->em->getRepository('AppBundle:Ticket')->getTicketsNumber($value);

        if ($ticketsNumber >= 1000)
        {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}