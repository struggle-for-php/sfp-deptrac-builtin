<?php
namespace Foo\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Foo\Repository\UserRepository;

final class UserShowAction implements RequestHandlerInterface
{
    private UserRepository $userRepository;  

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        \header("aaa");
    }
}