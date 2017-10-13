<?php

namespace Rougin\Authsum\Checker;

use Doctrine\ORM\QueryBuilder;

/**
 * Doctrine Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DoctrineChecker extends AbstractChecker implements CheckerInterface
{
    /**
     * @var string
     */
    protected $accessor = 'getPassword';

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * @param \Doctrine\ORM\EntityManager $manager
     * @param string                      $entity
     */
    public function __construct(\Doctrine\ORM\EntityManager $manager, $entity)
    {
        $this->entity = $entity;

        $this->manager = $manager;
    }

    /**
     * Sets the method name for the password accessor.
     *
     * @param  string $accessor
     * @return self
     */
    public function accessor($accessor = 'getPassword')
    {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * Checks if exists in the said collection.
     *
     * @throws \Doctrine\ORM\NoResultException
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    public function check(array $credentials)
    {
        $repository = $this->manager->getRepository($this->entity);

        $builder = $repository->createQueryBuilder('x');

        $fields = array_keys($credentials);
        $values = array_values($credentials);

        $builder->where('x.' . $fields[0] . ' = :' . $fields[0]);
        $builder->setParameter($fields[0], $values[0]);

        if ($this->hashed === false) {
            $builder->andWhere('x.' . $fields[1] . ' = :' . $fields[1]);
            $builder->setParameter($fields[1], $values[1]);

            return $builder->getQuery()->getSingleResult();
        }

        return $this->verify($builder, $values[1]);
    }

    /**
     * Checks the password value against the hashed result.
     *
     * @param  \Doctrine\ORM\QueryBuilder $builder
     * @param  boolean                           $value
     * @return boolean|mixed
     */
    protected function verify(QueryBuilder $builder, $value)
    {
        $result = $builder->getQuery()->getSingleResult();

        $checked = password_verify($value, $result->{$this->accessor}());

        return ($checked === true) ? $result : false;
    }
}
