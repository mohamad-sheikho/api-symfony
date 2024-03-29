<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Article;
use App\Entity\UserOwnedInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($resourceClass,$queryBuilder);

    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($resourceClass,$queryBuilder);
    }
    private function addWhere(string $resourceClass, QueryBuilder $queryBuilder){

        $reflectionClass = new \ReflectionClass($resourceClass);

        if ($reflectionClass->implementsInterface(UserOwnedInterface::class)){
            $alias = $queryBuilder->getRootAliases()[0];
            $user = $this->security->getUser();
            if($user){
                $queryBuilder->andWhere("$alias.user = :current_user")->setParameter('current_user', $this->security->getUser()->getId() );

            } else{
                $queryBuilder->andWhere("$alias.user IS NULL");

            }
        }
    }
}