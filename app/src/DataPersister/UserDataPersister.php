<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $DecoratedDataPersister;
    private $userPasswordEncoder;

    public function __construct(DataPersisterInterface $DecoratedDataPersister, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->DecoratedDataPersister = $DecoratedDataPersister;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $this->DecoratedDataPersister->persist($data);
    }

    public function remove($data)
    {
        $this->DecoratedDataPersister->remove($data);
    }
}
