<?php

namespace App\System;

/**
 * Interface IdentityInterface
 * @package App\System
 */
interface IdentityInterface
{

    /**
     * @param string $username
     * @return mixed
     */
    public function findByUsername(string $username);

    /**
     * @param string $password
     * @return mixed
     */
    public function comparePassword(string $password);

    /**
     * @param string $token
     * @return mixed
     */
    public function setToken(string $token);

    /**
     * @param string $token
     * @return mixed
     */
    public function findByToken(string $token);

}