<?php

require_once "config.php";

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    public $error;
    private $stmt;

    public function __construct(
        $host = null,
        $user = null,
        $pass = null,
        $dbname = null
    ) {
        $this->host = $host ?? $this->host;
        $this->user = $user ?? $this->user;
        $this->pass = $pass ?? $this->pass;
        $this->dbname = $dbname ?? $this->dbname;

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbh->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
    
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql);
    
        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }
    
        return $this->execute();
    }
    
    public function find($table, $column, $value) {
        $query = "SELECT * FROM $table WHERE $column = :value LIMIT 1";
        $this->query($query);
        $this->bind(':value', $value);
        return $this->single();
    }

    public function update($table, $id_field, $id_value, $data)
    {
        $assign = "";

        foreach ($data as $field => $value) {
            if ($field == $id_field) {
                continue;
            }

            if ($assign == '') {
                $assign = "$field=:$field";
            } else {
                $assign .= ",$field=:$field";
            }
        }

        $query = "UPDATE $table SET $assign WHERE `$id_field`=:$id_field ";

        $this->query($query);

        foreach ($data as $field => $value) {
            if ($field == $id_field) {
                continue;
            }
            $this->bind(":$field", $value);
        }

        $this->bind(":$id_field", $id_value);

        $ret = $this->execute();

        return $ret;
    }
    

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute($nameValuePairArray = null) {
    try {
        if (is_array($nameValuePairArray) && !empty($nameValuePairArray)) {
            return $this->stmt->execute($nameValuePairArray);
        } else {
            return $this->stmt->execute();
        }
    } catch (PDOException $e) {
        die("❌ Errore PDO: " . $e->getMessage());
    }
}


    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function close()
    {
        $this->dbh = null;
        $this->stmt = null;
    }
}
?>
