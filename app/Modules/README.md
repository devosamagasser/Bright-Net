# Modular Architecture Overview

Each domain module follows a layered structure so that responsibilities remain isolated and easy to reason about.

```
Module
|-- Application      # Use cases, DTOs, command/query services
|-- Domain           # Entities, aggregates, repositories, value objects
|-- Infrastructure   # Persistence, external gateways, module providers
|-- Presentation     # HTTP controllers, requests, API resources, views
\-- Support          # Helpers specific to the module
```

## Shared Utilities

Cross-cutting concerns live inside `app/Modules/Shared`. Common traits, contracts, or base service providers should be added there so individual modules can extend them.

## Service Providers

- `app/Modules/ModulesServiceProvider` loads all module providers defined in `config/modules.php`.
- Each module has an infrastructure provider extending `AbstractModuleServiceProvider`. Override the template methods (`registerBindings`, `bootRoutes`, etc.) to wire the module together while keeping the lifecycle consistent across modules.

## Working With Modules

1. Register bindings in the module provider (`Infrastructure/Providers`).
2. Implement domain logic under `Domain`, persisting through repositories placed in `Infrastructure`.
3. Expose application use cases via services or HTTP endpoints from the `Application` and `Presentation` layers.

This skeleton keeps the codebase ready for future tasks without locking in implementation details prematurely.
