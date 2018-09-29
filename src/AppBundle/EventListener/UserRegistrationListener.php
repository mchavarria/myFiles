<?php

namespace AppBundle\EventListener;

use AppBundle\Data\UserTypes;
use AppBundle\Entity\User;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserRegistrationListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * UserRegistrationListener constructor.
     *
     * @param UrlGeneratorInterface  $router
     */
    public function __construct(UrlGeneratorInterface $router) {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize'
        ];
    }

    /**
     * Set user as 'free'.
     *
     * @param GetResponseUserEvent $event
     */
    public function onRegistrationInitialize(GetResponseUserEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $user->setType(UserTypes::FREE_USER);
    }

    /**
     * Redirect to login after successful registration.
     *
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $url = $this->router->generate('fos_user_security_login');

        $event->setResponse(new RedirectResponse($url));
    }
}