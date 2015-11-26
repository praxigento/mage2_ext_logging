<?php
/**
 * Script to register M2-module.
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
use Magento\Framework\Component\ComponentRegistrar as Registrar;
use Praxigento\Logging\Config as Config;

Registrar::register(Registrar::MODULE, Config::MODULE, __DIR__);