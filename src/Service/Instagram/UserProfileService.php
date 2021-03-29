<?php

/**
 * This file is part of the @modelsua-api package.
 */

namespace App\Service\Instagram;


/**
 * Class UserProfileService.
 */
class UserProfileService extends AbstractDoctrine
{
    private $primaryKey = 'id';

    public function get($userId)
    {
        return $this->fetch(
            'SELECT * FROM instagram_user_profile iup WHERE iup.user_id = :user_id LIMIT 1',
            [
                'user_id' => $userId,
            ]
        ) ?: null;
    }

    public function save(array $data)
    {
        if (array_key_exists($this->primaryKey, $data)) {
            $this->update('instagram_user_profile', $data, $this->getIdentifier($data));

            return $data;
        }

        $data[$this->primaryKey] = $this->insert('instagram_user_profile', $data);

        return $data;
    }

    private function getIdentifier($data)
    {
        $identifier                    = [];
        $identifier[$this->primaryKey] = $data[$this->primaryKey];

        return $identifier;
    }
}