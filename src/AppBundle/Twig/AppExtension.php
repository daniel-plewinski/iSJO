<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    protected $validator;

    public function __construct(\AppBundle\Validation\TimeValidator $validator)
    {
        $this->validator = $validator;
    }

    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('isOneDayOld', [$this, 'isOneDayOld'])];
    }

    public function isOneDayOld(\DateTime $date)
    {
        return $this->validator->isOneDayOld($date);
    }



}

