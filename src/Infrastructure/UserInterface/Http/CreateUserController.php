<?php

namespace App\Infrastructure\UserInterface\Http;

use App\Domain\Service\CreateUser;
use App\Infrastructure\UserInterface\Http\Request\CreateUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/users', name: 'create_user', methods: [Request::METHOD_POST])]
final readonly class CreateUserController
{
    public function __construct(private CreateUser $createUser)
    {
    }

    public function __invoke(
        #[MapRequestPayload] CreateUserRequest $createUserRequest,
    ): JsonResponse {
        ($this->createUser)($createUserRequest->email, $createUserRequest->firstname, $createUserRequest->lastname);

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
