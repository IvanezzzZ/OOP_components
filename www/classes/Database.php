<?php

class Database
{
    private static $instance = null;

    private $pdo, $error = false, $result, $query, $count;

    private function __construct()
    {
        try {
             $this->pdo = new PDO('mysql:host=' . Config::get('mysql.db_host') . '; dbname=' . Config::get('mysql.db_name') . ';', Config::get('mysql.db_login'), Config::get('mysql.db_password'));
        } catch (PDOException $exception){
            echo $exception->getMessage();
        }
    }
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params))
        {
            $i = 1;
            foreach ($params as $param)
            {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }

        if (!$this->query->execute())
        {
            $this->error = true;
        }
        $this->result = $this->query->fetchAll(PDO::FETCH_OBJ);
        $this->count = $this->query->rowCount();

        return $this;
    }

    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where = [])
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $params = [])
    {
        $values = '';
        foreach ($params as $param)
        {
            $values .= '?,';
        }
        $values = rtrim($values, ',');
        $fields = implode(', ', array_keys($params));

        $sql = "INSERT INTO $table ($fields) VALUES ($values)";

        if (!$this->query($sql, $params)->getError())
        {
            return true;
        }
        return false;
    }

    public function update($table, $id, $params)
    {
        $set = '';
        foreach ($params as $field => $value)
        {
            $set .= $field . '=' . '?' . ',';
        }
        $set = rtrim($set, ',');

        $sql = "UPDATE $table SET $set WHERE id = $id";
        if (!$this->query($sql, $params)->getError())
        {
            return true;
        }

        return false;
    }

    public function action($action, $table, $where)
    {
        if (count($where) === 3)
        {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            $operators = ['=', '>', '<', '>=', '<=', '!='];

            if (in_array($operator, $operators))
            {
                $sql = "$action FROM $table WHERE $field $operator ?";
                if (!$this->query($sql, [$value])->getError())
                {
                    return $this;
                }
            }
        }
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function first()
    {
        return $this->result[0];
    }
}