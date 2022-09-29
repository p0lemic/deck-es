<?php

declare(strict_types=1);

namespace Deck\Tests\Feature\Consumer;

use GuzzleHttp\Client;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;

class GameConsumerTest extends TestCase
{
    public function testGetGameById()
    {
        $id = "611cf3e5-c517-4aff-996e-446a02b308d2";
        $currentPlayerId = '674f74a7-f1be-4576-a80d-c5ef0260ad76';

        $request = new ConsumerRequest();
        $request->setMethod("GET")
            ->setPath("/api/game/" . $id)
            ->addHeader("Accept", "application/json");

        $matcher = new Matcher();
        $response = new ProviderResponse();
        $response->setStatus(200)
            ->addHeader("Content-Type", "application/json")
            ->setBody(
                [
                    'id' => $id,
                    'players' => $matcher->eachLike([
                        'id' => $currentPlayerId,
                        'hand' => $matcher->eachLike([
                            $matcher->regex('clubs','[a-zA-Z]+')
                        ]),
                        'wonCards' => []
                    ]),
                    'currentPlayerId' => $currentPlayerId,
                    'cardsOnTable' => []
                ]
            );

        $config = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->uponReceiving( 'A get request to /api/game/{id}')
            ->with($request)
            ->willRespondWith($response);

        $client = new Client(["base_uri" => $config->getBaseUri()]);
        $consumer = new GameConsumer($client);

        $game = $consumer->getGameById($id);

        $this->assertTrue($builder->verify());

        $this->assertEquals($id, $game->id());
    }
}
