<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\CheeseListing;
use App\Entity\CheeseNotification;
use Doctrine\ORM\EntityManagerInterface;

class CheeseListingDataPersister implements DataPersisterInterface
{
    private $DecoratedDataPersister;
    private $entityManager;

    public function __construct(DataPersisterInterface $DecoratedDataPersister, EntityManagerInterface $entityManager)
    {
        $this->DecoratedDataPersister = $DecoratedDataPersister;
        $this->entityManager = $entityManager;
    }

    public function supports($data): bool
    {
        return $data instanceof CheeseListing;
    }

    /**
     * @param CheeseListing $data
     */
    public function persist($data)
    {
        // get the original data: the way it looked before it was changed by ApiPlatform
        $originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($data);
        // dump($originalData);
        $wasAlreadyPublished = $originalData['isPublished'] ?? false; // because if he create new data the array will be empty
        if ($data->getIsPublished() && !$wasAlreadyPublished) {
            $notification = new CheeseNotification($data, 'CheeseListing was created!');
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }
        
        $this->DecoratedDataPersister->persist($data);
    }

    public function remove($data)
    {
        $this->DecoratedDataPersister->remove($data);
    }
}
