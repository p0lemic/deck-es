<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use DateInterval;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;

class RefreshTokenListener implements EventSubscriberInterface
{
    private int $jwtTokenTTL;
    private bool $cookieSecure;

    public function __construct($ttl)
    {
        $this->jwtTokenTTL = $ttl;
        $this->cookieSecure = false;
    }

    public function setRefreshToken(AuthenticationSuccessEvent $event): void
    {
        /** @var JWTAuthenticationSuccessResponse $response */
        $response = $event->getResponse();
        $data = $event->getData();
        $refreshToken = $data['refresh_token'];
        unset($data['refresh_token']);
        $event->setData($data);

        if ($refreshToken) {
            $response->headers->setCookie(
                new Cookie(
                    'REFRESH_TOKEN', $refreshToken, (
                new DateTime())
                    ->add(new DateInterval('PT' . $this->jwtTokenTTL . 'S')), '/', null, $this->cookieSecure
                )
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => [
                ['setRefreshToken'],
            ],
        ];
    }
}
