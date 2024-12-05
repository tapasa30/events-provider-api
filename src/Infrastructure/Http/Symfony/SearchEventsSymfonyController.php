<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Symfony;

use App\Application\Query\Event\SearchEventsQuery;
use App\Application\Query\Event\SearchEventsResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class SearchEventsSymfonyController extends AbstractController
{
    use HandleTrait;

    public function __construct(private readonly MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $rawStartsAt = $request->query->get('starts_at');
        $rawEndsAt = $request->query->get('ends_at');

        $startAtDate = null;
        $endsAtDate = null;

        if ($rawStartsAt !== null) {
            $startAtDate = \DateTime::createFromFormat('Y-m-d', $rawStartsAt);

            if ($startAtDate === false) {
                return new JsonResponse([
                    'error' => 'starts_at has invalid format',
                    'data' => null
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($rawEndsAt !== null) {
            $endsAtDate = \DateTime::createFromFormat('Y-m-d', $rawEndsAt);

            if ($endsAtDate === false) {
                return new JsonResponse([
                    'error' => 'ends_at has invalid format',
                    'data' => null
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        $searchEventsQuery = new SearchEventsQuery($startAtDate, $endsAtDate);

        try {
            /** @var SearchEventsResponse $searchEventsResponse */
            $searchEventsResponse = $this->handle($searchEventsQuery);
        } catch (HandlerFailedException $exception) {
            return new JsonResponse([
                'error' => $exception->getPrevious()->getMessage(),
                'code' => $exception->getCode(),
                'data' => null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'error' => null,
            'data' => ['events' => $searchEventsResponse->toPrimitives()]
        ], Response::HTTP_OK);
    }
}
