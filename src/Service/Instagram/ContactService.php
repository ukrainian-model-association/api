<?php

/**
 * This file is part of the @modelsua-api package.
 */

namespace App\Service\Instagram;


/**
 * Class InstagramContactService.
 */
class ContactService extends AbstractDoctrine
{
    public function findFilled()
    {
        return $this->fetchAll(
            'SELECT * FROM user_contacts uc WHERE uc.key = :key AND uc.value LIKE :value',
            [
                'key'   => 'instagram',
                'value' => '%instagram.com/%',
            ]
        );
    }
}