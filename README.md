## System Architecture Overview
The system is designed using **Hexagonal Architecture** to ensure maintainability and scalability while adding new features. This design includes patterns like **CQRS (Command Query Responsibility Segregation)** and **DDD (Domain-Driven Design)** for clear separation of concerns and modularity.

### Key Layers:
1. **Domain Layer**
    - Defines the core business model, primarily the `Event` entity.
    - Contains interfaces to abstract business logic and ensure testability.

2. **Application Layer**
    - Houses the core logic, such as commands and queries, following the CQRS pattern.
    - Facilitates interaction between the Domain and Infrastructure layers.

3. **Infrastructure Layer**
    - Implements platform-specific logic.
    - Two key subfolders:
        - **Client**: Integrates with external APIs, such as the Provider's API.
        - **EventProvider**: Contains implementations for each event provider integration.

This layered approach ensures clear separation of concerns, making the system easy to extend and maintain.

---

## Code Essentials
The code adheres to the following principles:
- **Type-safe OOP**: Ensures robust and predictable behavior through strict typing.
- **Clean Code**: Follows best practices for readability and maintainability.
- **SOLID Principles**: Encourages modular and scalable design.

### Frameworks and Tools:
- **Symfony, used for**:
    - CQRS message buses for Commands and Queries.
    - Http Controllers.
    - CLI commands.
- **Serializer**: Library for parsing XML data from the external event provider, for example.

### Caching results
- **Redis** is used to store search data for fast retrieval.

---

## Adding a New Event Provider
To integrate a new event provider into the system, follow these steps:

1. **Define a New Event Provider**
    - Implement a new class under the `Infrastructure/EventProvider` folder to fetch events from the provider's API.
    - If necessary, create the API client inside the `Infrastructure/Client` folder.

2. **Map the Event Data**
    - Convert the provider-specific data format to the standardized `ProviderEventDTO` DTO model as it is specified in the Interface.

3. **Register the Provider**
    - The system is configured to auto-register `EventProviderInterface` child classes so the synchronize command should run the new Provider

4. **Don't forget to make a Test**
    - Write unit tests to validate the data mapping.

---

## Running the Application

### Prerequisites
- Docker and docker-compose installed

First of all we need our .env configured, in this case we will configure the localhost environment
```bash
cp .env.dist .env.local
```

For localhost we will use this `DATABASE_URL` value:
```
DATABASE_URL="mysql://root:root@mysql:3306/events?serverVersion=8.0.32&charset=utf8mb4"
```

Then we can start the application with:
```bash
make run
```
When the command finishes, the application will be running but the Database will be empty.

The app will be running in this url: [http://localhost:8181](http://localhost:8181)

Swagger docs will be available at: [http://localhost:8044](http://localhost:8044)

To Synchronize and populate the events from the Providers (Only one is integrated in the system: Example Provider) this command needs to run:
```bash
make synchronize_provider_events
```

To list Make available commands
```bash
make help
```

---

## Making It Faster
To ensure the system performs efficiently, even with high traffic and large datasets, consider the following strategies:

### Search endpoint query limits
- Right now the search is providing a non-limited event list, it should be paginated

### Caching
- Cache query results should be cleared when new data is fetched from the provider.

### Asynchronous Processing
- Leverage a message queue system like **RabbitMQ** or **Kafka** to process provider events asynchronously when they are retrieved from the provider.
- This helps decouple real-time requests from heavy computations.

### Scalability
- Add more replicas for read-heavy services or API endpoints during peak traffic.
- Configure Nginx for load balancing between server replicas

### Database
- Use a database like **Elasticsearch** to be the main database for the search results data origin

### Monitoring and Profiling
- Implement tools like **Prometheus** or **New Relic** to monitor performance and detect bottlenecks.
- Profile code regularly to identify and optimize slow queries or processes.
