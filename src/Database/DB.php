<?php

namespace App\Database;

use PDO;
use PDOException;

class DB
{
    protected PDO $pdo;
    protected QueryBuilder $queryBuilder;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = new QueryBuilder();
    }

    public function query($sql)
    {
        return $statement = $this->pdo->prepare($sql);
    }

    function insert($table, $data): int
    {
        if (is_array($data)) {
            $columns = '';
            $bindv = '';
            $values = null;
            foreach ($data as $column => $value) {
                if ($column !== "id") {
                    $columns .= '`' . $column . '`,';
                    $bindv .= '?,';
                    $values[] = $value;
                }
            }
            $columns = substr($columns, 0, -1);
            $bindv = substr($bindv, 0, -1);

            $sql = "INSERT INTO {$table}({$columns}) VALUES ({$bindv})";

            try {
                $stmt = $this->query($sql);
                $stmt->execute($values);
                return $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return -1;
    }

    function selectAll($table): array
    {
        $this->queryBuilder = new QueryBuilder();
        $this->queryBuilder->select("*");
        $this->queryBuilder->from($table);

        $sql = $this->queryBuilder->__toString();

        $stmt = $this->query($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $rows;
    }

    function selectWhere($table, $data, $all = false)
    {
        $filter = "";
        $keys = array_keys($data);
        $values = array_values($data);

        for ($i = 0; $i < count($keys); $i++) {
            if ($values[$i] != "")
                $filter .= $keys[$i] . "='" . $values[$i] . "' AND ";
        }

        if ($all)
            $columns = "*";
        else
            $columns = implode(",", $keys);

        $this->queryBuilder = new QueryBuilder();
        $this->queryBuilder->select($columns);
        $this->queryBuilder->from($table);
        $this->queryBuilder->where($filter);
        
        $sql = substr($this->queryBuilder->__toString(), 0, -4) . ";";

        $stmt = $this->query($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $rows;
    }

    function selectAllWithJoin($table1, $table2, $data, string $join1, string $join2): array
    {
        $filter = "";
        $keys = array_keys($data);
        $values = array_values($data);

        for ($i = 0; $i < count($keys); $i++) {
            if ($values[$i] != "")
                $filter .= $keys[$i] . "='" . $values[$i] . "' AND ";
        }

        $inners = "{$table1}.{$join1} = {$table2}.{$join2}";

        $sql = "SELECT {*} FROM {$table1} INNER JOIN {$table2} ON {$inners}";

        $stmt = $this->query($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    // només una condició
    function selectWhereWithJoin(string $table, string $columns = null, string $tableJoin1, string $tableJoin2, array $data, string $join1, $all = false): array
    {
        $conditions = "";
        $keys = array_keys($data);
        $values = array_values($data);

        if ($all && $columns != "") {
            $columns = "*";
        } else {
            $columns = implode(',', $columns);
        }

        for ($i = 0; $i < count($keys); $i++) {
            if ($values[$i] != "")
                $conditions .= $keys[$i] . "='" . $values[$i] . "' AND ";
            $conditions = substr($conditions, 0, -5) . ";";
        }

        if ($tableJoin1 != null && $tableJoin2 != null) {
            $inner = "INNER JOIN {$tableJoin1} ON {$tableJoin1}.{$join1}_" . lcfirst($tableJoin2) . " = {$tableJoin2}.{$join1}";
        }

        /*
        SELECT 
        *
        FROM
        Library, User
        INNER JOIN
        User_Library ON User_Library.id_user = User.id
        WHERE User_Library.id_user = 2;
        */
        $sql = "SELECT {$columns} FROM {$table}, {$tableJoin2} {$inner} WHERE {$conditions}";

        $stmt = $this->query($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $rows;
    }

    function update(string $table, array $data, $key)
    {
        if ($data && $data[$key] != "") {

            $changes = "";
            $value = $data[$key];
            $keys = array_keys($data);
            $values = array_values($data);

            for ($i = 0; $i < count($keys); $i++)
                $changes .= $keys[$i] . "='" . $values[$i] . "',";

            $changes = substr($changes, 0, -1);
            $cond = "$key='{$value}'";
            $sql = "UPDATE {$table} SET {$changes} WHERE {$cond}";

            $stmt = $this->query($sql);
            $res = $stmt->execute();

            if ($res)
                return true;
        } else
            return false;
    }

    function delete(string $data, $key, $value)
    {

        $sql = "DELETE FROM {$data} WHERE {$key} = '{$value}'";

        $stmt = $this->query($sql);
        $res = $stmt->execute();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }



}