<?php

namespace App\Controller;


use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;


class MeController extends AbstractController
{


    public function __construct(private Security $security){}

    public function __invoke(): ?UserInterface
    {

        $user = $this->security->getUser();
        return $user;
    }

}