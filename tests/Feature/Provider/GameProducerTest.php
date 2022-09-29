<?php

declare(strict_types=1);

namespace Deck\Tests\Feature\Provider;

use GuzzleHttp\Psr7\Uri;
use PhpPact\Standalone\ProviderVerifier\Model\VerifierConfig;
use PhpPact\Standalone\ProviderVerifier\Verifier;
use PHPUnit\Framework\TestCase;

class GameProducerTest extends TestCase
{
    public function testGameConsumer()
    {
        $config = new VerifierConfig();
        $config->setProviderName('gameProvider') // Providers name to fetch.
            ->setProviderVersion('1.0.0') // Providers version.
            ->setProviderBranch('main') // Providers git branch
            ->setProviderBaseUrl(new Uri('http://localhost:80')) // URL of the Provider.
            ->setPublishResults(true);

        $verifier = new Verifier($config);
        $verifier->verifyFiles([__DIR__ . "/../../../pacts/gameconsumer-gameprovider.json"]);

        // This will not be reached if the PACT verifier throws an error, otherwise it was successful.
        $this->assertTrue(true, 'Pact Verification has failed.');
    }
}
