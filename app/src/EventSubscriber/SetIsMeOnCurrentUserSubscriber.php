<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class SetIsMeOnCurrentUserSubscriber implements EventSubscriberInterface
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
        
    }
    public function onKernelRequest(RequestEvent $event)
    {
        // the special key where API platform puts the "data" for the current API request
        // dd($event->getRequest()->attributes->get('data'));
        if (!$event->isMasterRequest()) {
            return;
        }
        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!$user) {
            return;
        }

        $user->setIsMe(true);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
