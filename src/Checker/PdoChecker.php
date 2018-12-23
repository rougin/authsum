<?php

namespace Rougin\Authsum\Checker;

/**
 * PDO Checker
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
    protected $query = 'SELECT * FROM %s WHERE %s';

    /**
     * @var string
     */
    protected $table;

    /**
     * Initializes the checker instance.
     *
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
        list($data, $table) = array(array(), $this->table);

        foreach ($credentials as $key => $value) {
            $parameter = "$key = '$value'";

            $data[] = (string) $parameter;
        }

        $query = sprintf($this->query, $table, implode(' AND ', $data));

        $result = $this->pdo->query($query)->fetch(\PDO::FETCH_ASSOC);

        $this->hashed === true && $result = $this->verify($credentials);

        return $result;
    }

    /**
     * Checks the password value against the hashed result.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    protected function verify(array $credentials)
    {
        $fields = array_keys($credentials);

        $where = $fields[0] . ' = "' . $credentials[$fields[0]] . '"';

        $query = sprintf($this->query, $this->table, $where);

        $item = $this->pdo->query($query)->fetch(\PDO::FETCH_ASSOC);

        $password = $credentials[$fields[1]];

        $verified = password_verify($password, $item[$fields[1]]);

        return $verified === true ? $item : false;
    }
}
