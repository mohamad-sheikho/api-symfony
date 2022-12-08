<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;


class MeController extends AbstractController
{
private $security;

    public function __construct(Security $security){}

    public function __invoke()
    {
       $user = $this->security->getUser();
       return $user;
    }

}