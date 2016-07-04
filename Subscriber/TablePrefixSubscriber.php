<?php

namespace DoctrinePrefixBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ClassMetadata;

class TablePrefixSubscriber implements EventSubscriber
{
    protected $prefix = '';

    /**
     * Constructor
     *
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = (string)$prefix;

        if (false === empty($this->prefix) && '_' !== substr($this->prefix, -1)) {
            $this->prefix .= '_';
        }
    }

    /**
     * Get subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    /**
     * Load class meta data event
     *
     * @param LoadClassMetadataEventArgs $args
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        if (empty($this->prefix)) {
            return;
        }

        /** @var ClassMetadata $classMetadata */
        $classMetadata = $args->getClassMetadata();

        if (false === strpos($classMetadata->getTableName(), $this->prefix)) {
            $tableName = $this->prefix . $classMetadata->getTableName();
            $classMetadata->setPrimaryTable(['name' => $tableName]);
        }

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide'] == true) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];

                if (false !== strpos($mappedTableName, $this->prefix)) {
                    continue;
                }

                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}