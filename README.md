# MyAdmin DRBL Backups Plugin

[![Tests](https://github.com/detain/myadmin-drbl-backups/actions/workflows/tests.yml/badge.svg)](https://github.com/detain/myadmin-drbl-backups/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/detain/myadmin-drbl-backups/version)](https://packagist.org/packages/detain/myadmin-drbl-backups)
[![Total Downloads](https://poser.pugx.org/detain/myadmin-drbl-backups/downloads)](https://packagist.org/packages/detain/myadmin-drbl-backups)
[![License](https://poser.pugx.org/detain/myadmin-drbl-backups/license)](https://packagist.org/packages/detain/myadmin-drbl-backups)

DRBL (Diskless Remote Boot in Linux) backup handling plugin for the MyAdmin control panel. This plugin provides event-driven integration for managing DRBL-based backup services, including menu registration, requirement loading, and settings management through the Symfony EventDispatcher component.

## Requirements

- PHP 8.1 or higher
- ext-soap
- Symfony EventDispatcher ^5.0 / ^6.0 / ^7.0

## Installation

```sh
composer require detain/myadmin-drbl-backups
```

## Usage

The plugin registers itself through the MyAdmin plugin system via event hooks. It provides:

- **Menu integration** -- Registers admin menu entries for backup management
- **Requirement loading** -- Loads DRBL class definitions and abuse-handling functions
- **Settings management** -- Integrates with the MyAdmin settings system

## Running Tests

```sh
composer install
vendor/bin/phpunit
```

## License

Licensed under the LGPL-2.1. See [LICENSE](https://www.gnu.org/licenses/old-licenses/lgpl-2.1.en.html) for details.
