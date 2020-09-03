<?php

namespace mdoutreluingne\RecaptchaBundle\Constraints;

use ReCaptcha\ReCaptcha;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintValidator;

class RecaptchaValidator extends ConstraintValidator
{
    private $requestStack;
    private $reCaptcha;

    public function __construct(RequestStack $requestStack, \ReCaptcha\ReCaptcha $reCaptcha)
    {
        $this->requestStack = $requestStack;
        $this->reCaptcha = $reCaptcha;
    }

    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();
        $recaptchaResponse = $request->request->get('g-recaptcha-response');
        if (empty($recaptchaResponse)) {
            $this->addViolation($constraint); 
            return;
        }
        $response = $this->reCaptcha
            ->setExpectedHostname($request->getHost())
            ->verify($recaptchaResponse, $request->getClientIp());
        if (!$response->isSuccess()) {
            $this->addViolation($constraint);
        }
    }

    public function addViolation(Constraint $constraint)
    {
        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
