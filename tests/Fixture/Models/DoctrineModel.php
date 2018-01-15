<?php

namespace Rougin\Authsum\Fixture\Models;

/**
 * Doctrine Model
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 *
 * @Entity @Table(name="users")
 */
class DoctrineModel
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @Column(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string")
     *
     * @var string
     */
    protected $username;

    /**
     * @Column(type="string")
     *
     * @var string
     */
    protected $password;

    /**
     * Returns the ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name.
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the username.
     *
     * @param  string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the password.
     *
     * @param  string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the password.
     *
     * @return string
     */
    public function getPasswordCustom()
    {
        return $this->password;
    }
}
