<?php

namespace Rougin\Authsum\Checker;

/**
 * Eloquent Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class EloquentChecker implements CheckerInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
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
        $class = 'Illuminate\Database\Eloquent\Model';

        $item = $this->model->where($credentials)->first();

        $result = $this->verify($this->model, $credentials, $item);

        return (is_a($result, $class)) ? $result : false;
    }

    /**
     * Checks the hash of the password if it is hashed.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  array                               $data
     * @param  boolean|mixed                       $result
     * @return boolean|mixed
     */
    protected function verify($model, $data, $result)
    {
        list($keys, $values) = array(array_keys($data), array_values($data));

        if ($this->hashed === true) {
            $item = $model->where(array($keys[0] => $values[0]))->first();

            if (is_null($item) === false) {
                $checked = password_verify($values[1], $item->{$keys[1]});

                $result = ($checked === true) ? $item : false;
            }
        }

        return $result;
    }
}
