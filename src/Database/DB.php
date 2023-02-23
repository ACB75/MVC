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

        if (!$all)
            $columns = implode(",", $keys);
        else
            $columns = "*";

        $this->queryBuilder->select($columns);
        $this->queryBuilder->from($table);
        $this->queryBuilder->where($filter);

        $sql = substr($this->queryBuilder->__toString(), 0, -5) . ";";

        $stmt = $this->query($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $rows;
    }

    function selectAllWithJoin($table1, $table2, array $fields = null, string $join1, string $join2): array
    {
        if (is_array($fields)) {
            $columns = implode(',', $fields);

        } else {
            $columns = "*";
        }

        $inners = "{$table1}.{$join1} = {$table2}.{$join2}";

        $sql = "SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners}";

        $stmt = $this->query($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    // només una condició
    function selectWhereWithJoin($table1, $table2, array $fields = null, string $join1, string $join2, array $conditions): array
    {
        if (is_array($fields)) {
            $columns = implode(',', $fields);
        } else {
            $columns = "*";
        }

        $inners = "{$table1}.{$join1} = {$table2}.{$join2}";
        $cond = "{$conditions[0]}='{$conditions[1]}'";

        $sql = "SELECT {$columns} FROM {$table1} INNER JOIN {$table2} ON {$inners} WHERE {$cond} ";


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
        } 
        else
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