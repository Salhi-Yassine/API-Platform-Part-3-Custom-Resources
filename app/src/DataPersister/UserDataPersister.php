<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $DecoratedDataPersister;
    private $userPasswordEncoder;
    private $loggerInterface;

    public function __construct(ContextAwareDataPersisterInterface $DecoratedDataPersister, UserPasswordEncoderInterface $userPasswordEncoder, LoggerInterface $loggerInterface)
    {
        $this->DecoratedDataPersister = $DecoratedDataPersister;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->loggerInterface = $loggerInterface;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        dump($context);
        if (($context['collection_operation_name'] ?? null) === 'put') {
            $this->loggerInterface->info(sprintf('User %s being updated', $data->getId()));
        }
        if (!$data->getId()) {
            // take any actions needed for a new user
            // send registration email
            // integrate into some CRM or payment system

            $this->loggerInterface->info(sprintf('User %s just registered. Eureka!', $data->getEmail()));
        }
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $this->DecoratedDataPersister->persist($data);
    }

    public function remove($data, array $context = [])
    {
        $this->DecoratedDataPersister->remove($data);
    }
}
