<?php

namespace Rougin\Authsum\Checker;

/**
 * Eloquent Checker
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
        $this->model = is_string($model) ? new $model : $model;
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
        $columns = (array) array_keys($credentials);

        $username = array($columns[0] => $credentials[$columns[0]]);

        $result = $this->model->where($username)->first();

        $password = (string) $credentials[$columns[1]];

        $hashed = $result ? $result->{$columns[1]} : '';

        $checked = password_verify($password, $hashed);

        return $checked !== false ? $result : false;
    }
}
