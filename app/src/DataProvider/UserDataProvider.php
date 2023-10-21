<?php
namespace App\DataProvider;
use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;

class UserDataProvider implements  ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $collectionDataProvider;

    public function __construct(CollectionDataProvider $collectionDataProvider)
    {
        $this->collectionDataProvider = $collectionDataProvider;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) 
    {
        return $this->collectionDataProvider->getCollection($resourceClass,$operationName,$context);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool 
    {
        return $resourceClass === User::class;
    }


}