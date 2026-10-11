<?php

/*
 * Additional rules or rules to override.
 * These rules will be added to default rules or will override them if the same key already exists.
 */

$additionalRules = [];
$rulesProvider = new Facile\CodingStandards\Rules\CompositeRulesProvider([
    new Facile\CodingStandards\Rules\DefaultRulesProvider(),
    new Facile\CodingStandards\Rules\RiskyRulesProvider(),
    new Facile\CodingStandards\Rules\ArrayRulesProvider($additionalRules),
]);

$config = new PhpCsFixer\Config();
$config->setRules($rulesProvider->getRules());
$config->setRiskyAllowed(true);

$finder = new PhpCsFixer\Finder();

/*
 * You can set manually these paths:
 */
$autoloadPathProvider = new Facile\CodingStandards\AutoloadPathProvider();
$finder->in([...$autoloadPathProvider->getPaths(), 'examples']);

$config->setFinder($finder);

return $config;
