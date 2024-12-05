<?php

declare(strict_types=1);

namespace App\Infrastructure\CLI\Symfony;

use App\Application\Command\Event\CreateEventCommand;
use App\Domain\DTO\ProviderEvent\ProviderEventDTO;
use App\Domain\Exception\EventAlreadyExistsException;
use App\Domain\Service\EventProviderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:event-provider:synchronize', description: 'Synchronize events from provider')]
class SynchronizeProviderEventsSymfonyCommand extends Command
{
    use HandleTrait;

    /**
     * @param iterable<EventProviderInterface> $eventProviders
     */
    public function __construct(
        private readonly iterable $eventProviders,
        private readonly MessageBusInterface $commandBus,
    ) {
        parent::__construct();

        $this->messageBus = $commandBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->eventProviders as $eventProvider) {
            $this->storeProviderEvents($eventProvider->fetchEvents());
        }

        return Command::SUCCESS;
    }

    /**
     * @param array<ProviderEventDTO> $providerEvents
     * @return void
     */
    private function storeProviderEvents(array $providerEvents): void
    {
        foreach ($providerEvents as $providerEvent) {
            $rawZones = $this->generateRawZones($providerEvent);

            $createEventCommand = new CreateEventCommand(
                $providerEvent->getExternalId(),
                $providerEvent->getTitle(),
                $providerEvent->getStartDateTime(),
                $providerEvent->getEndDateTime(),
                $rawZones,
            );

            try {
                $this->handle($createEventCommand);
            } catch (HandlerFailedException $exception) {
                if ($exception->getPrevious() instanceof EventAlreadyExistsException) {
                    continue;
                }
            }
        }
    }

    /**
     * @param ProviderEventDTO $providerEvent
     * @return array
     */
    private function generateRawZones(ProviderEventDTO $providerEvent): array
    {
        $rawZones = [];

        foreach ($providerEvent->getZones() as $zone) {
            $rawZones[] = [
                'external_id' => $providerEvent->getExternalId(),
                'name' => $zone->getName(),
                'price' => $zone->getPrice(),
                'capacity' => $zone->getCapacity(),
                'is_numbered' => $zone->isNumbered(),
            ];
        }

        return $rawZones;
    }
}
