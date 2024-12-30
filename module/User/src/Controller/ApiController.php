<?php

declare(strict_types=1);

namespace User\Controller;

use Application\View\JsonCollection;
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
        return new JsonModel(['data' => $this->userRepository->findOneById($id)->getArrayCopy()]);
    }

    public function getList()
    {
        $collection = new JsonCollection(
            'id',
            $this->userRepository->fetchAll()->toArray(),
            'firstName',
        );
        return new JsonModel($collection->toArray());
    }

    public function create($data)
    {
        return new JsonModel(['data' => $this->userRepository->save($data)->getArrayCopy()]);
    }
}
