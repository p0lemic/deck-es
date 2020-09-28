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
use Deck\Domain\Shared\AggregateId;
use InvalidArgumentException;
use Swagger\Annotations as SWG;
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
     * @SWG\Response(
     *     response=200,
     *     description="Available games"
     * )
     *
     * @SWG\Tag(name="Game")
     *
     * @param GamesListQuery $listGames
     * @return Response
     */
    public function list(GamesListQuery $listGames): Response
    {
        try {
            $games = $listGames->execute();

            return $this->createApiResponse($games);
        } catch (Throwable $exception) {
            var_dump($exception->getTraceAsString());
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create new game from an existing table id
     *
     * @SWG\Response(
     *     response=201,
     *     description="Game created successfully"
     * )
     * @SWG\Parameter(
     *     name="game",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="table_id", type="string")
     *     )
     * )
     * @SWG\Tag(name="Game")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $tableId = $request->get('table_id');

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
     * @SWG\Response(
     *     response=200,
     *     description="Game loaded successfully"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Parameter(
     *     name="game",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="id", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Game")
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
     * @SWG\Response(
     *     response=200,
     *     description="Card was drawn successfully"
     * )
     * @SWG\Parameter(
     *     name="game",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="game_id", type="string")
     *     )
     * )
     * @SWG\Tag(name="Game")
     *
     * @param Security $security
     * @param Request $request
     * @return Response
     */
    public function playerDraw(Security $security, Request $request): Response
    {
        $gameId = $request->get('game_id');

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
