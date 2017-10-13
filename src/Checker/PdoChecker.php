<?php

namespace Rougin\Authsum\Checker;

/**
 * PDO Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class PdoChecker extends AbstractChecker implements CheckerInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $table;

    /**
     * @param \PDO   $pdo
     * @param string $table
     */
    public function __construct(\PDO $pdo, $table)
    {
        $this->pdo = $pdo;

        $this->table = $table;
    }

    /**
     * Checks if exists in the said collection.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    public function check(array $credentials)
    {
        $query = 'SELECT * FROM %s WHERE %s';

        list($data, $table) = array(array(), $this->table);

        if ($this->hashed === false) {
            foreach ($credentials as $key => $value) {
                $parameter = "$key = '$value'";

                array_push($data, $parameter);
            }

            $query = sprintf($query, $table, implode(' AND ', $data));

            $statement = $this->pdo->query($query);

            return $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return $this->verify($query, $credentials);
    }

    /**
     * Checks the password value against the hashed result.
     *
     * @param  string $query
     * @param  array  $credentials
     * @return boolean|mixed
     */
    protected function verify($query, array $credentials)
    {
        list($fields, $values) = array(array_keys($credentials), array_values($credentials));

        $query = sprintf($query, $this->table, $fields[0] . ' = "' . $values[0] . '"');

        $statement = $this->pdo->query($query);

        $item = $statement->fetch(\PDO::FETCH_ASSOC);

        return password_verify($values[1], $item[$fields[1]]) ? $item : false;
    }
}
