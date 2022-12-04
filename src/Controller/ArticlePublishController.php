<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticlePublishController extends AbstractController
{

    public function __invoke(Article $data): article{
        $data->setOnline(true);
        return $data;
    }
}