<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\DTO\Event\EventSummaryDTO;
use App\Domain\Entity\Event;
use App\Domain\Repository\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventDoctrineRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $workEntry): void
    {
        $this->getEntityManager()->persist($workEntry);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<EventSummaryDTO>
     */
    public function findSummaryByDateRange(?\DateTimeInterface $startsAtDate, ?\DateTimeInterface $endsAtDate): array
    {
        $queryBuilder = $this->createQueryBuilder('event');

        $queryBuilder
            ->select(
        'new '.EventSummaryDTO::class.'(
                    event.id,
                    event.title,
                    event.startDateTime,
                    event.endDateTime,
                    MIN(zone.price) as minPrice,
                    MAX(zone.price) as maxPrice  
                )'
            )
            ->innerJoin('event.zones', 'zone')
            ->groupBy('event.id');

        if ($startsAtDate !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('event.startDateTime', ':start_date'))
                ->setParameter('start_date', $startsAtDate->format('Y-m-d 00:00:00'));
        }

        if ($endsAtDate !== null) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('event.endDateTime', ':end_date'))
                ->setParameter('end_date', $endsAtDate->format('Y-m-d 23:59:59'));
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByExternalId(string $externalId): ?Event
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }
}
