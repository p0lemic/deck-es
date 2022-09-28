<?php

declare(strict_types=1);

namespace Deck\Tests\Contract;

use Mmal\OpenapiValidator\Validator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;

class RegisterControllerTest extends WebTestCase
{
    const SPEC_PATH = __DIR__ . '/../../spec/openapi.yaml';
    static Validator $openApiValidator;

    static public function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$openApiValidator = new Validator(Yaml::parse(file_get_contents(self::SPEC_PATH)));
    }

    public function testLoadGame()
    {
        $this->makeRequest('POST', '/api/user');
    }

    protected function makeRequest(
        $method,
        $uri,
        $content = ''
    ) {
        $client = static::createClient();
        $client->request(
            $method,
            $uri,
            [
                'email' => 'javieroman+46@gmail.com',
                'password' => '123456'
            ]
        );
        $response = $client->getResponse();

        $result = self::$openApiValidator->validateBasedOnRequest(
            $uri,
            $method,
            $response->getStatusCode(),
            json_decode($response->getContent(), true)
        );
        self::assertFalse($result->hasErrors(), $result->getOperation());
    }
}
