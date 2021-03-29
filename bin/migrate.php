#!/usr/bin/env php
<?php
/**
 * This file is part of the @modelsua-api package.
 */

/**
 * Main
 */
(new class {
    public const DB_MAIN   = 'main';
    public const DB_ORIGIN = 'origin';

    /** @var PDO[] */
    private array $db;

    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->db = [
            self::DB_MAIN   => new PDO(
                sprintf('pgsql:host=db.modelsua.local;port=5432;dbname=%s;user=modelsua;password=123', 'modelsua_api')
            ),
            self::DB_ORIGIN => new PDO(
                sprintf('pgsql:host=db.modelsua.local;port=5432;dbname=%s;user=modelsua;password=123', 'modelsua')
            ),
        ];
    }

    /**
     *
     */
    public function execute(): void
    {
        $query = $this->db[self::DB_ORIGIN]->query('select * from user_auth ua');
        $users = $query->fetchAll();

        foreach ($users as $user) {
            $createdAt = (new DateTime())->setTimestamp((int) $user['created_ts']);
            $query     = $this->db[self::DB_MAIN]->prepare(
                'insert into person (id, username, password, created_at) VALUES (:id, :username, :password, :created_at)'
            );
            $query->execute(
                [
                    'id'         => $user['id'],
                    'username'   => $user['email'],
                    'password'   => $user['password'],
                    'created_at' => $createdAt->format('Y-m-d H:i:s'),
                ]
            );

            var_dump($query->errorInfo());
        }
    }
})->execute();
