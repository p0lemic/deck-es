<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\DrawCardCommand;
use Deck\Application\Game\GamesListQuery;
use Deck\Application\Game\LoadGame;
use Deck\Application\Game\LoadGameRequest;
use Deck\Domain\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Table\TableReadModel;
use InvalidArgumentException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Throwable;
use function var_dump;

class GameController extends AbstractRenderController
{
    /**
     * List available tables
     *
     * @OA\Response(
     *     response=200,
     *     description="Available games"
     * )
     *
     * @OA\Tag(name="Game")
     *
     * @param GamesListQuery $listGames
     * @return Response
     */
    public function list(GamesListQuery $listGames): Response
    {
        $games = $listGames->execute();

        return (new JsonResponse)
            ->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION)
            ->setData(
                array_map(
                    static fn(GameReadModel $game) => $game->toArray(),
                    $games
                )
            );
    }

    /**
     * Create new game from an existing table id
     *
     * @OA\Response(
     *     response=201,
     *     description="Game created successfully"
     * )
     *
     * @OA\RequestBody(
     *     request="table",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Table"),
     * )
     *
     * @OA\Tag(name="Game")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $tableId = $request->get('id');

        try {
            Assertion::notNull($tableId, 'Table Id can\'t be null');

            $createGameCommand = new CreateGameCommand($tableId);
            $this->execute($createGameCommand);

            return $this->createApiResponse(['id' => $createGameCommand->gameId()->value()], Response::HTTP_CREATED);
        } catch (InvalidArgumentException|AssertionFailedException|InvalidPlayerNumber $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Load a game
     *
     * @OA\Response(
     *     response=200,
     *     description="Game loaded successfully"
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\RequestBody(
     *     request="game",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Game"),
     * )
     *
     * @OA\Tag(name="Game")
     *
     * @param LoadGame $loadGame
     * @param Request $request
     * @return Response
     */
    public function load(
        LoadGame $loadGame,
        Request $request
    ): Response {
        try {
            $id = $request->get('id');

            $game = $loadGame->execute(new LoadGameRequest($id));

            return $this->createApiResponse($game);
        } catch (Throwable $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Draw a new card from deck
     *
     * @OA\Response(
     *     response=200,
     *     description="Card was drawn successfully"
     * )
     *
     * @OA\RequestBody(
     *     request="game",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Game"),
     * )
     *
     * @OA\Tag(name="Game")
     *
     * @param Security $security
     * @param Request $request
     * @return Response
     */
    public function playerDraw(Security $security, Request $request): Response
    {
        $gameId = $request->get('id');

        try {
            $user = $security->getUser();

            /** @var AggregateId $userId */
            $userId = $user ? $user->id() : null;

            if (null === $userId) {
                throw new AuthenticationException('You should be logged in to create a new table.');
            }

            Assertion::notNull($gameId, 'Game Id can\'t be null');

            $drawCardCommand = new DrawCardCommand($gameId, $userId->value());
            $this->execute($drawCardCommand);

            return $this->createApiResponse(['id' => $drawCardCommand->gameId()->value()], Response::HTTP_OK);
        } catch (Throwable $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
