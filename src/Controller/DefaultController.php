<?php

/**
 * This file is part of the @modelsua\api package.
 */

namespace App\Controller;

use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/instagram/account/{username}")
     *
     * @param string $username
     * @return JsonResponse
     * @throws JsonException
     */
    final public function instagramAccount(string $username): JsonResponse
    {
        $output  = exec(sprintf('bash %s/../../bin/instagram.get_account.sh "%s"', __DIR__, $username));
        $account = json_decode($output, true, 512, JSON_THROW_ON_ERROR);

        return JsonResponse::create($account, Response::HTTP_OK);
    }
}
