<?php
namespace CoreBundle\Exception;
/**
 * Class UnsupportedObjectException
 * @package CoreBundle\Exception
 */
class UnsupportedObjectException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($entity, $className)
    {
        if (is_object($entity)) {
            parent::__construct(sprintf('Unsupported exception: % must implement %s', $entity, $className));
        } else {
            parent::__construct('Unsupported exception: not a object');
        }
    }
}