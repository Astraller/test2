<?php

namespace App\Model;

use \App\System\BaseModel;
use App\System\IdentityInterface;

/**
 * Class User
 * @package App\Model
 *
 * @property string $username
 * @property string $password
 * @property string $token
 */
class User extends BaseModel implements IdentityInterface
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'users';
    }

    /**
     * @param string $username
     * @return User|BaseModel
     */
    public function findByUsername(string $username): self
    {
        return self::model()->where(['username' => $username])->one();
    }

    /**
     * @param string $passwordHash
     * @return bool
     */
    public function comparePassword(string $passwordHash)
    {
        return $this->password === $passwordHash;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        self::model()->where(['id' => $this->id])->update(['token' => $token]);
    }

    /**
     * @param string $token
     * @return User|BaseModel
     */
    public function findByToken(string $token): self
    {
        return self::model()->where(['token' => $token])->one();
    }
}