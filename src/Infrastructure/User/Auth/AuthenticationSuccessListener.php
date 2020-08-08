<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use DateInterval;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\HttpFoundation\Cookie;

class AuthenticationSuccessListener
{
    private int $jwtTokenTTL;
    private bool $cookieSecure;

    public function __construct($ttl)
    {
        $this->jwtTokenTTL = $ttl;
        $this->cookieSecure = false;
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): JWTAuthenticationSuccessResponse
    {
        /** @var JWTAuthenticationSuccessResponse $response */
        $response = $event->getResponse();
        $data = $event->getData();
        $tokenJWT = $data['token'];
        unset($data['token']);
        $event->setData($data);

        $response->headers->setCookie(new Cookie('BEARER', $tokenJWT, (
        new DateTime())
            ->add(new DateInterval('PT' . $this->jwtTokenTTL . 'S')), '/', null, $this->cookieSecure));

        return $response;
    }
}
