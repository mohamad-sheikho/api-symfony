<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;


class MeController extends AbstractController
{
private $security;

    public function __construct(Security $security){}

    public function __invoke()
    {
       $user = $this->security->getUser(true);
       return $user;
    }

}