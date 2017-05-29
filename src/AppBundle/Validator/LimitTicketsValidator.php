<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

class LimitTicketsValidator extends ConstraintValidator
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $ticketsNumber = $this->em->getRepository('AppBundle:Ticket')->getTicketsNumber($value);
        $commande = $this ->context->getObject();
        if ($ticketsNumber + $commande->getTicketsNumber() >= 1000)
        {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
            ;
        }
    }
}
