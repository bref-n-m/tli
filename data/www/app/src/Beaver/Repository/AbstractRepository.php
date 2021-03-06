<?php

namespace Beaver\Repository;

use PDO;
use ReflectionClass;

abstract class AbstractRepository
{
    const PAGINATION_LIMIT = 15;

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
     * @param int $currentPage
     * @param string $orderedBy
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function getAllPaginated(int $currentPage, string $orderedBy = ''): array
    {
        $res = [];

        // get the entities
        $query = 'SELECT * FROM `' . $this->tableName . '`';
        if ($orderedBy) {
            $query .= 'ORDER BY `' . $orderedBy . '`';
        }
        $query .= 'LIMIT ' . self::PAGINATION_LIMIT * $currentPage . ', ' . self::PAGINATION_LIMIT . ';';
        $stmt = $this->db->query($query);

        $reflector = $this->getReflector();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $res[] = $reflector->newInstanceArgs($row);
        }

        // get the amount of pages
        $stmt = $this->db->query('SELECT COUNT(*) as amount FROM `' . $this->tableName . '`');
        $amount = $stmt->fetch(PDO::FETCH_ASSOC)['amount'];

        return [
            'pagination' => [
                'pageMax'     => ceil($amount / self::PAGINATION_LIMIT) - 1,
                'currentPage' => $currentPage,
            ],
            'res'        => $res,
        ];
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
            if (array_key_last($columns) != $key) {
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

    /**
     * @param array $params => ['columnName' => 'value']
     *
     * @return bool
     */
    public function insert(array $params): bool
    {
        if (!$params) {
            return false;
        }

        $query = 'INSERT INTO ' . $this->tableName . ' (';
        $values = '';

        foreach ($params as $columnName => $value) {
            $query .= '`' . $columnName . '`';
            $values .= ':' . $columnName;
            // Test if the current item is the last item

            if (array_key_last($params) != $columnName) {
                $query .= ', ';
                $values .= ', ';
            }
        }

        $query .= ') VALUES (' . $values . ')';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // $stmt->errorCode returns '00000' if the request was executed successfully
        return $stmt->errorCode();
    }

    /**
     * @param array $params
     *
     * @param string $whereKey Must to be in $params
     *
     * @return bool
     */
    public function update(array $params, string $whereKey): bool
    {
        if (!$params) {
            return false;
        }

        $query = 'UPDATE `' . $this->tableName . '` SET ';

        foreach ($params as $columnName => $value) {
            $query .= '`' . $columnName . '` = :' . $columnName;

            // Test if the current item is the last item
            if (array_key_last($params) != $columnName) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE `' . $whereKey . '` = :' . $whereKey;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // $stmt->errorCode returns '00000' if the request was executed successfully
        return $stmt->errorCode();
    }

    /**
     * @param array $whereParams
     *
     * @return bool
     */
    public function delete(array $whereParams): bool
    {
        if (!$whereParams) {
            return false;
        }

        $query = 'DELETE FROM `' . $this->tableName . '` WHERE ';

        foreach ($whereParams as $columnName => $value) {
            $query .= '`' . $columnName . '` = :' . $columnName;

            // Test if the current type is the last of $types
            if (array_key_last($whereParams) != $columnName) {
                $query .= ' and';
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($whereParams);

        // $stmt->errorCode returns '00000' if the request was executed successfully
        return $stmt->errorCode();
    }
}
