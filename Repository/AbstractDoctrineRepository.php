<?php
/**
 * Repository - doctrine
 *
 * @copyright (c) 2014 John Pancoast
 * @author John Pancoast <shideon@gmail.com>
 */

namespace Shideon\Bundle\SmeeApiBundle\Model\Repository;

use \Symfony\Component\Validator\ValidatorInterface;
use \Doctrine\ORM\EntityManager;

use Shideon\Bundle\SmeeApiBundle\Model\RepositoryInterface;

use Shideon\Bundle\SmeeApiBundle\Model\DependencyTrait as Dependency;

/**
 * Repository - doctrine
 *
 * !!! NOTE THAT THIS CLASS WILL SOON BE DEFUNCT IN PLACE OF MONGO !!!
 * We're leaving it in case it can still be useful.
 *
 * @author John Pancoast <shideon@gmail.com>
 */
abstract class AbstractDoctrineRepository implements RepositoryInterface
{
    use Dependency\EntityManagerTrait;
    use Dependency\ValidatorTrait;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $em Doctrine entity manager
     */
    public function __construct(EntityManager $em, ValidatorInterface $validator)
    {
        $this->setEntityManager($em);
        $this->setValidator($validator);
    }

    /**
     * Get entity we're working with
     *
     * @return string Entity object name
     */
    abstract public function getEntity();

    /**
     * {@inheritDoc}
     */
    public function find(SearchCriteria $searchCriteria)
    {
        $em = $this->getEntityManager();
        $em->getRepostiory($this->getEntity());

        return $em->matching($searchCriteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findOne($id, $field = '')
    {
        $em = $this->getEntityManager();

        if ($field) {
            return $em->getRepository($this->getEntity())->findOneBy([$field => $id]);
        }

        return $em->find($this->getEntity(), $id);
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        $em = $this->getEntityManager();

        return $em->getRepository($this->getEntity())->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data)
    {
        $em = $this->getEntityManager();

        $name = $this->getEntity();
        $entity = self::updateEntityFromArray(new $name(), $data);

        $em->persist($entity);
        $em->flush();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function update($id, array $data)
    {
        $em = $this->getEntityManager();

        $entity = $em->find($this->getEntity(), $id);

        if (!$entity) {
            return;
        }

        $entity = self::updateEntityFromArray($entity, $data);

        $em->persist($entity);
        $em->flush();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id)
    {
        $em = $this->getEntityManager();
        $em->remove($em->find($this->getEntity(), $id));
        $em->flush();
    }

    /**
     * Update an entity with an array of data
     *
     * @param  object $entity Entity object
     * @param  array  $data   Array of data
     * @return object Updated entity object
     */
    protected static function updateEntityFromArray($entity, array $data = array())
    {
        if (!is_object($entity)) {
            throw new \Exception('$entity must be an object');
        }

        foreach ($data as $k => $v) {
            $k = str_replace(' ', '', ucwords(str_replace('_', ' ', $k)));
            $method = 'set'.ucfirst($k);
            $entity->{$method}($v);
        }

        return $entity;
    }
}
