# Monolog Cascade integration to Magento 2

[![Build Status](https://travis-ci.org/praxigento/mage2_ext_logging.svg)](https://travis-ci.org/praxigento/mage2_ext_logging/)
[![Coverage Status](https://coveralls.io/repos/praxigento/mage2_ext_logging/badge.svg?branch=master&service=github)](https://coveralls.io/github/praxigento/mage2_ext_logging?branch=master)


## Installation

Add to your project's `composer.json`:

      "require": {
        "praxigento/mage2_ext_logging": "~0.1"
      }

## Usage

Default configuration file is in `var/log/logging.yaml` (see `src/etc/di.xml`).

Get logger with ObjectManager:

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ...
    ) {
        $this->_logger = $logger;
        ...
    }

Then log your messages:

    $this->_logger->info("'Get account' operation is called.");


## Configuration sample

    disable_existing_loggers: true
    formatters:
        dashed:
            class: Monolog\Formatter\LineFormatter
            format: "%datetime%-%channel%.%level_name% - %message%\n"
    handlers:
        debug:
            class: Monolog\Handler\StreamHandler
            level: DEBUG
            formatter: dashed
            stream: /.../var/log/cascade_debug.log
        system:
            class: Monolog\Handler\StreamHandler
            level: INFO
            formatter: dashed
            stream: /.../var/log/cascade_system.log
        exception:
            class: Monolog\Handler\StreamHandler
            level: EMERGENCY
            formatter: dashed
            stream: /.../log/cascade_exception.log
    processors:
        web_processor:
            class: Monolog\Processor\WebProcessor
    loggers:
        main:
            handlers: [debug, system, exception]
            processors: [web_processor]