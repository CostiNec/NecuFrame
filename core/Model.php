<?php

namespace core;

use core\ConnectDB;
use PDOException;
use PDO;
use Symfony\Component\VarDumper\VarDumper;
use core\Exception as CatchError;

abstract class Model
{
    protected $data=array();
    protected $columns;
    protected $extra=array();
    const PRIMARYKEY = 'id';

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->data = $data;
        }
    }

    public function __set($name, $value)
    {
        $array = array_map('strtolower', $this->columns);
        $name = strtolower($name);
        if(in_array($name, $array))
            $this->data[$name] = $value;
        else {
            throw (new CatchError('Column "'.$name .'" doesn\'t exists in your model',500));
        }
    }

    public function __get($name) {
        if (!empty($this->$name)) {
            return $this->$name;
        }
        $array = array_map('strtolower', $this->columns);
        $name = strtolower($name);
        if(in_array($name, $array)) {
            return $this->data[$name];
        } else {
            throw (new CatchError('Column "'.$name .'" doesn\'t exists in your model',500));
        }
    }

    public static function getTableName()
    {
        $modelName = get_called_class();

        if(defined($modelName.'::TABLE')) {
            $tableName = $modelName::TABLE;
        } else {
            $modelName = explode('\\',$modelName)[1];
            $tableName = strtolower($modelName).'s';
        }

        return $tableName;
    }

    public static function getConn()
    {
        $instance = ConnectDb::getInstance();
        return $instance->getConnection();
    }

    public static function find($value, $field = self::PRIMARYKEY)
    {
        $models = self::where($field,$value);

        if (!count($models)) {
            return null;
        }

        return $models[0];
    }

    public static function get($value,$key = self::PRIMARYKEY,$columns=[])
    {
        $modelName = get_called_class();

        $tableName = self::getTableName();

        $conn = self::getConn();

        $modelName = str_replace('\\','/',$modelName);

        require_once(__DIR__ . "/../" . $modelName . ".php");

        if(count($columns) == 0) {
            $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $key .' = ' . '"' .$value .'"';
            $stmt = $conn->query($sql);
            $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql = 'SELECT ';
            $selected = '';
            foreach ($columns as $column) {
                $selected = $selected . ',?';
            }
            $selected = substr($selected,1);
            $sql = $sql . $selected . ' FROM ' . $tableName . ' WHERE ' . $key .' = ' . '"' .$value .'"';

            $stmt= $conn->prepare($sql);
            $stmt->execute($columns);

            $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $models = [];

        $modelName = str_replace('/','\\',$modelName);

        foreach ($responses as $response) {
            $model = new $modelName($response);
            foreach ($response as $key => $one) {
                $model->$key = $one;
            }
            array_push($models, $model);
        }

        return $models;
    }

    public function insert()
    {
        $tableName = self::getTableName();

        $conn = self::getConn();

        $sql = 'INSERT INTO '.$tableName.' (';
        $columns = '';
        $values = '';
        $parameters = [];
        foreach ($this->data as $key => $data) {
            if($key != self::PRIMARYKEY && !in_array($key,$this->extra)) {
                $columns = $columns . ',' . $key;
                $values = $values . ',?';
                array_push($parameters,$data);
            }
        }
        $columns = substr($columns,1);
        $values = substr($values,1);

        $sql = $sql . $columns . ') VALUES (' . $values . ');';

        $stmt= $conn->prepare($sql);
        $response = $stmt->execute($parameters);
        $this->{self::PRIMARYKEY} = $conn->lastInsertId();

    }

    public function update()
    {
        $tableName = self::getTableName();
        $conn = self::getConn();

        $sql = 'UPDATE ' . $tableName .' SET ';
        $updated = '';
        $dates = [];
        foreach ($this->data as $key => $data) {
            if (!in_array($key,$this->extra)) {
                $updated = $updated . ','. $key .' = ?';
                array_push($dates,$data);
            }
        }
        $updated = substr($updated,1);
        $sql = $sql . $updated . ' WHERE ' . self::PRIMARYKEY . '=' . '"'.$this->data[self::PRIMARYKEY].'"';

        $stmt = $conn->prepare($sql);
        $stmt->execute($dates);
    }

    public function save()
    {
        if(!isset($this->data[self::PRIMARYKEY]))
        {
            $this->insert();
        } else if (count(self::where(self::PRIMARYKEY,$this->{self::PRIMARYKEY})) == 0) {
            $this->insert();
        } else {
            $this->update();
        }
    }

    public function delete()
    {
        return self::deleteByField(self::PRIMARYKEY,$this->{self::PRIMARYKEY});
    }

    public static function deleteByField($field, $statement = null, $value = null)
    {
        $tableName = self::getTableName();

        if($value == null && $statement == null) {
            $value = $field;
            $field = self::PRIMARYKEY;
            $statement = '=';
        }
        else if($value == null) {
            $value = $statement;
            $statement = '=';
        }

        $sql = 'DELETE FROM '.$tableName.' WHERE '.$field.' '.$statement.'  ?;';

        return self::customPrepareQuery($sql,[$value]);
    }

    public static function where($field, $statement = null, $value = null)
    {
        $tableName = self::getTableName();

        if($value == null && $statement == null) {
            $value = $field;
            $field = self::PRIMARYKEY;
            $statement = '=';
        }
        else if($value == null) {
            $value = $statement;
            $statement = '=';
        }

        $sql = 'SELECT * FROM '.$tableName.' WHERE '.$field.' '.$statement.'  ?;';

        $responses = self::customPrepareQuery($sql,[$value])->fetchAll(PDO::FETCH_ASSOC);

        if (empty($responses)) {
            return null;
        }

        $modelName = get_called_class();

        $modelName = str_replace('/','\\',$modelName);

        $models = [];


        foreach ($responses as $response) {
            $model = new $modelName($response);

            foreach ($response as $key => $one) {
                $model->$key = $one;
            }

            $models[] = $model;
        }

        return $models;
    }

    public static function customQuery($query)
    {
        $conn = self::getConn();
        $results = $conn->query($query);

        return $results;
    }

    public static function all()
    {
        $tableName = self::getTableName();
        $conn = self::getConn();

        $sql = 'SELECT * FROM '.$tableName;
        $result = $conn->query($sql);

        $responses = $result->fetchAll(PDO::FETCH_ASSOC);

        $modelName = get_called_class();

        $models = [];
        $modelName = str_replace('/','\\',$modelName);

        foreach ($responses as $response) {
            $model = new $modelName($response);
            foreach ($response as $key => $one) {
                $model->$key = $one;
            }
            array_push($models, $model);
        }

        return $models;
    }

    public static function customPrepareQuery($query,$parameters)
    {
        $conn = self::getConn();
        $stmt= $conn->prepare($query);
        $stmt->execute($parameters);
        return $stmt;
    }
}
