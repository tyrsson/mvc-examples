<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use User\Repository\UserRepository;

final class ApiController extends AbstractRestfulController
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function get($id)
    {
        $user = $this->userRepository->get($id);
        return new JsonModel(
            [
                'data' => $user->getArrayCopy(),
            ]
        );
    }

    public function create($data)
    {
        $user = $this->userRepository->save($data);
        return new JsonModel(
            [
                'data' => $user->getArrayCopy(),
            ]
        );
    }
}
