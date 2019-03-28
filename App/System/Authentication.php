<?php

namespace App\System;

use App\App;
use App\System\Exceptions\UnauthorizedHttpException;
use Ramsey\Uuid\Uuid;

/**
 * Class Authentication
 * @package App\System
 */
class Authentication
{
    /**
     * @var
     */
    private $salt;
    /**
     * @var
     */
    private $pepper;
    /**
     * @var IdentityInterface
     */
    private $entityModel;
    /**
     * @var IdentityInterface
     */
    private $loggedUser;

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
        $this->salt = App::inst()->Configuration->security['salt'];
        $this->pepper = App::inst()->Configuration->security['pepper'];
        $this->entityModel = new (App::inst()->Configuration->security['salt']);
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     * @throws UnauthorizedHttpException
     */
    public function authorize(string $username, string $password): string
    {
        /** @var IdentityInterface $user */
        $user = $this->entityModel->findByUsername($username);
        $passwordHash = $this->getHash($password);
        $valid = $user->comparePassword($passwordHash);
        if ($valid) {
            return $this->login($user);
        } else {
            throw new UnauthorizedHttpException('Wrong username or password');
        }
    }

    /**
     * @param string $password
     * @return string
     */
    public function getHash(string $password): string
    {
        return hash('sha256', $this->pepper . $password . $this->salt);
    }

    /**
     * @param IdentityInterface $identity
     * @return string
     */
    private function login(IdentityInterface $identity)
    {
        $this->loggedUser = $identity;
        $token = $this->generateToken();
        $this->loggedUser->setToken($token);
        return $token;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return Uuid::uuid4()->toString();
    }
}