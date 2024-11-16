<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Error;
use Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PdoSource extends Source implements WithUsername, WithPassword
{
    /**
     * @var string
     */
    protected $passwordField;

    /**
     * @var string
     */
    protected $passwordValue;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $usernameField;

    /**
     * @var string
     */
    protected $usernameValue;

    /**
     * @var boolean
     */
    protected $withHash = true;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->pdo = $pdo;
    }

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid()
    {
        $username = $this->usernameField;

        $table = $this->table;

        $query = "SELECT * FROM $table WHERE $username = ?";

        $error = new Error;

        try
        {
            /** @var \PDOStatement */
            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array($this->usernameValue));

            /** @var array<string, string> */
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        catch (\Exception $e)
        {
            return $this->setError($e->getMessage());
        }

        $hash = $row[$this->passwordField];

        $value = $this->passwordValue;

        $samePass = password_verify($value, $hash);

        if (! $this->withHash)
        {
            $samePass = $row[$this->passwordField] === $value;
        }

        $value = $this->usernameValue;

        $sameUser = $row[$this->usernameField] === $value;

        if ($sameUser && $samePass)
        {
            return $this->setResult();
        }

        return $this->setError();
    }

    /**
     * Sets the password field.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordField($password)
    {
        $this->passwordField = $password;

        return $this;
    }

    /**
     * Sets the password value.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordValue($password)
    {
        $this->passwordValue = $password;

        return $this;
    }

    /**
     * Sets the table name.
     *
     * @param string $table
     *
     * @return self
     */
    public function setTableName($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Sets the username field.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameField($username)
    {
        $this->usernameField = $username;

        return $this;
    }

    /**
     * Sets the username value.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameValue($username)
    {
        $this->usernameValue = $username;

        return $this;
    }

    /**
     * @return self
     */
    public function withoutHash()
    {
        $this->withHash = false;

        return $this;
    }
}
