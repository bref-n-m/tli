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
     * @param string $columnName
     * @param $value
     *
     * @return array|null
     *
     * @throws \ReflectionException
     */
    protected function getByRow(string $columnName, $value): array
    {
        $res = [];
        $stmt = $this->db->prepare('
            SELECT *
            FROM `' . $this->tableName . '`
            WHERE `'. $columnName .'` = :'. $columnName .'
        ');
        $stmt->execute([$columnName => $value]);

        $reflector = $this->getReflector();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = $reflector->newInstanceArgs($row);
        }

        return $res;
    }

    /**
     * @param array $columns => ['columnName' => value , ...] -> i.e : ['idK' => 12 , 'idS' => 13]
     *
     * @return array|null
     *
     * @throws \ReflectionException
     */
    public function getByRows(array $columns): array
    {
        if (!$columns) {
            throw new \Exception('$columns shouldn\'t be empty or null');
        }

        $query = 'SELECT * FROM `' . $this->tableName . '` WHERE ';

        foreach ($columns as $key => $value) {
            $query .= ' `' . $key . '` = :' . $key;

            // Test if the current type is the last of $types
            if (end($columns) != $columns[$key]) {
                $query .= ' and';
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($columns);

        $reflector = $this->getReflector();

        $res = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = $reflector->newInstanceArgs($row);
        }

        return $res;
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

    /**
     * @param array $objects
     *
     * @return mixed|null
     */
    protected function getOne(array $objects)
    {
        return !$objects ? null : $objects[0];
    }
}
