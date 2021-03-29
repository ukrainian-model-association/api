<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Doctrine\EventListener;

use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Class FixPostgreSQLDefaultSchemaListener.
 */
class FixPostgreSQLDefaultSchemaListener
{
    /**
     * @param GenerateSchemaEventArgs $args
     *
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->getSchemaManager();

        if (!$schemaManager instanceof PostgreSqlSchemaManager) {
            return;
        }

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if (!$args->getSchema()
                ->hasNamespace($namespace)) {
                $args->getSchema()
                    ->createNamespace($namespace);
            }
        }
    }
}
