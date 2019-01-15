<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity
{

    const ENTITY_ID = 'id';
    const ENTITY_CREATE = 'entity_create';
    const ENTITY_UPDATE = 'entity_update';
    const ENTITY_REMOVE = 'entity_remove';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    protected $entity_create;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $entity_update;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $entity_remove;

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    final public function lifecycleCreate()
    {

        if (empty($this->EntityCreate)) {
            $this->entity_create = new \DateTime("now");
        }
    }

    /**
     * @ORM\PreUpdate
     * @throws \Exception
     */
    final public function lifecycleUpdate()
    {

        $this->entity_update = new \DateTime("now");
    }

    /**
     * @throws \Exception
     */
    final public function __toArray()
    {

        $array = get_object_vars($this);
        foreach ($array as $key => $value) {
            if ($value instanceof \DateTime) {
                $array[$key] = $value->format('d.m.Y H:i:s');
            }
        }

        return $array;
    }

    /**
     * @return \DateTime
     */
    public function getEntityCreate()
    {

        if (is_object($this->entity_create)) {
            return clone $this->entity_create;
        }
        return $this->entity_create;
    }

    /**
     * @return null|\DateTime
     */
    public function getEntityUpdate()
    {

        if (is_object($this->entity_update)) {
            return clone $this->entity_update;
        }
        return $this->entity_update;
    }

    /**
     * @param AbstractEntity $requiredEntity
     *
     * @return \DateTime|null
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getEntityRemove(AbstractEntity $requiredEntity = null)
    {

        // Default
        if ($this->entity_remove) {
            return $this->entity_remove;
        }

        // Joined Element Overload
        if (null === $requiredEntity) {
            // No Overload
            return $this->entity_remove;
        } else {
            $method = 'getService' . (new \ReflectionClass($requiredEntity))->getShortName();
            if (method_exists($this, $method)) {
                /** @var bool|AbstractEntity $table_join */
                $table_join = $this->$method();
                if ($table_join) {
                    // Exists
                    if ($table_join->getEntityRemove()) {
                        return $table_join->getEntityRemove();
                    } else {
                        return $this->entity_remove;
                    }
                } else {
                    // Missing Element
                    return new \DateTime();
                }
            } else {
                // Missing Method
                return $this->entity_remove;
            }
        }
    }

    /**
     * @param bool $Toggle
     *
     * @return AbstractEntity
     * @throws \Exception
     */
    public function setEntityRemove($Toggle = true)
    {

        if ($Toggle) {
            $this->entity_remove = new \DateTime("now");
        } else {
            $this->entity_remove = null;
        }
        return $this;
    }

    /**
     * Return Object-Id
     * Fix: Doctrine - Entity can't be converted to 'string' while getting Entity-Id
     *
     * @return string
     */
    final public function __toString()
    {

        return strval($this->getId());
    }

    /**
     * @return integer
     */
    final public function getId()
    {

        return $this->id;
    }

    /**
     * @param integer $Id
     */
    final public function setId($Id)
    {

        $this->id = $Id;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    final public function getEntityShortName()
    {

        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    final public function getEntityFullName()
    {

        return (new \ReflectionClass($this))->getName();
    }

    /**
     * @param mixed $value
     * @return float
     */
    protected function sanitizeNumber($value)
    {

        if (is_numeric($value)) {
            // Default
            return (float)$value;
        } else {
            // Locale
            if (class_exists('Locale') && class_exists('NumberFormatter')) {
                $locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $convert = (new \NumberFormatter($locale, \NumberFormatter::TYPE_DOUBLE));
                if (false !== $convert->parse($value)) {
                    return (float)$convert->parse($value);
                }
            }
            // Fallback
            return (float)str_replace(',', '.', str_replace('.', '', $value));
        }
    }
}
