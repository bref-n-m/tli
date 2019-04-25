<?php

namespace Beaver\Repository;

use PDO;
use ReflectionClass;

abstract class AbstractRepository
{
    /** @var PDO */
    protected $db;

    /** @var string */
    protected $tableName;

    /** @var string */
    protected $entityName;

    /** @var string */
    protected $primaryKeyName;

    public function __construct(PDO $db, string $tableName, string $entityName, string $primaryKeyName)
    {
        $this->db = $db;
        $this->tableName = $tableName;
        $this->entityName = $entityName;
        $this->primaryKeyName = $primaryKeyName;
    }

    /**
     * @param $id
     *
     * @return object|null
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare('
            SELECT *
            FROM `' . $this->tableName . '`
            WHERE `'. $this->primaryKeyName .'` = :primaryKey
        ');
        $stmt->execute(['primaryKey' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $reflector = $this->getReflector();

        return $row ? $reflector->newInstanceArgs($row) : null;
    }

    /**
     * @return array
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getAll(): array
    {
        $res = [];
        $stmt = $this->db->query('
            SELECT *
            FROM `' . $this->tableName . '`
        ');

        $reflector = $this->getReflector();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = $reflector->newInstanceArgs($row);
        }

        return $res;
    }

    /**
     * Get entities from idS, idP or idK
     *
     * @param string $type
     * @param int $id
     *
     * @return array|null
     *
     * @throws \ReflectionException
     */
    protected function getByRow(string $type, int $id): ?array
    {
        $res = [];
        $stmt = $this->db->prepare('
            SELECT *
            FROM `' . $this->tableName . '`
            WHERE `'. $type .'` = :'. $type .'
        ');
        $stmt->execute([$type => $id]);

        $reflector = $this->getReflector();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = $reflector->newInstanceArgs($row);
        }

        return $res;
    }

    /**
     * @param array $ids => ['idS' => ... , 'idP' => ..., ...]
     * @param array $types => ['idS', 'idP', ...]
     *
     * @return object|null
     *
     * @throws \ReflectionException
     */
    public function getByRows(array $ids, array $types)
    {
        $query = 'SELECT * FROM `' . $this->tableName . '` WHERE ';

        $queryParameters = [];
        foreach ($types as $type) {
            if (!key_exists($type, $ids)) {
                throw new \Exception('$ids should contain all keys from $types');
            }
            $query .= ' `' . $type . '` = :' . $type;

            // Test if the current type is the last of $types
            if (end($types) != $type) {
                $query .= ' and';
            }

            $queryParameters[$type] = $ids[$type];
        }

        $stmt = $this->db->prepare($query);

        $stmt->execute($queryParameters);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $reflector = $this->getReflector();

        return $row ? $reflector->newInstanceArgs($row) : null;
    }

    /**
     * @return ReflectionClass
     *
     * @throws \ReflectionException
     */
    protected function getReflector(): ReflectionClass
    {
        $reflector = new ReflectionClass($this->entityName);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("The entity (" . $this->entityName .") is not instantiable");
        }

        return $reflector;
    }
}
