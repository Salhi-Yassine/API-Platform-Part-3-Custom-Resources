<?php
namespace App\DataProvider;
use ApiPlatform\Core\Bridge\Doctrine\Orm\CollectionDataProvider;
use ApiPlatform\Core\Bridge\Doctrine\Orm\ItemDataProvider;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;


class UserDataProvider implements  ContextAwareCollectionDataProviderInterface, DenormalizedIdentifiersAwareItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $collectionDataProvider;
    private $itemDataProvider;
    private $security;

    public function __construct(CollectionDataProvider $collectionDataProvider,ItemDataProvider $itemDataProvider  , Security $security)
    {
        $this->collectionDataProvider = $collectionDataProvider;
        $this->itemDataProvider = $itemDataProvider;
        $this->security = $security;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []) 
    {
        /** @var User[] $users */
        $users = $this->collectionDataProvider->getCollection($resourceClass,$operationName,$context);
        
        foreach ($users as $user) {
            // now is handled in a listener
            // $user->setIsMe($user === $this->security->getUser());
        }
        return $users;
    }

    public function getItem(string $resourceClass, /* array */ $id, string $operationName = null, array $context = [])
    {
        /** @var User|null $user */
        $user = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);
        // check if the user id exist in database
        if (null === $user) {
            return null;
        }
        // now is handled in a listener 
        // $user->setIsMe($user === $this->security->getUser());
        return $user;
    }


    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool 
    {
        return $resourceClass === User::class;
    }


}