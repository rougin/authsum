<?php

namespace Rougin\Authsum\Checker;

/**
 * Eloquent Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class EloquentChecker extends AbstractChecker implements CheckerInterface
{
    const ELOQUENT = 'Illuminate\Database\Eloquent\Model';

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Initializes the checker instance.
     *
     * @param \Illuminate\Database\Eloquent\Model|string $model
     */
    public function __construct($model)
    {
        is_string($model) && $model = new $model;

        $this->model = $model;
    }

    /**
     * Checks if exists in the said collection.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    public function check(array $credentials)
    {
        $result = $this->model->where($credentials)->first();

        $this->hashed && $result = $this->verify($credentials);

        return is_a($result, self::ELOQUENT) ? $result : false;
    }

    /**
     * Checks the hash of the password if it is hashed.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    protected function verify($credentials)
    {
        $columns = array_keys($credentials);

        $username = array($columns[0] => $credentials[$columns[0]]);

        $result = $this->model->where($username)->first();

        $password = $credentials[$columns[1]];

        $hashed = $result->{$columns[1]};

        $checked = password_verify($password, $hashed);

        $checked === false && $result = false;

        return $result;
    }
}
