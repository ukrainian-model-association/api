<?php

/**
 * This file is part of the @modelsua-api package.
 */

namespace App\Service\Instagram;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Statement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AbstractDoctrine.
 */
abstract class AbstractDoctrine
{
    /** @var Connection */
    private $db;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        /** @var Connection db */
        $this->db = $managerRegistry->getConnection();
    }

    protected function fetchAll(string $sql, array $params = [])
    {
        return $this
            ->execute($sql, $params)
            ->fetchAll();
    }

    protected function execute(string $sql, array $params = [])
    {
        $agent = $this->prepare($sql);
        $agent->execute($params);

        return $agent;
    }

    /**
     * @param string $sql
     *
     * @return Statement
     */
    protected function prepare(string $sql)
    {
        return $this->db->prepare($sql);
    }

    protected function fetch(string $sql, array $params = [])
    {
        return $this
            ->execute($sql, $params)
            ->fetch();
    }

    protected function insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    protected function update($table, $data, $identifier)
    {
        return $this->db->update($table, $data, $identifier);
    }
}
