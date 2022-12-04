<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ArticleCountController extends AbstractController
{
public function  __construct(private ArticleRepository $articleRepository)
{

}
public function __invoke(Request $request): int
{
    $onlineQuery = $request->get('online');
    $conditions = [];
    if($onlineQuery != null) {
        $conditions = ['online' => $onlineQuery != '0'];
    }


return $this->articleRepository->count($conditions);
}
}