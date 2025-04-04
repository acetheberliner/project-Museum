<?php

require_once "config.php";
require_once "interface_DB.php";

class Database implements IDatabase {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;

    private $stmt;
    public $error;

    public function __construct($host = null, $user = null, $pass = null, $dbname = null) {
        $this->host = $host ?? $this->host;
        $this->user = $user ?? $this->user;
        $this->pass = $pass ?? $this->pass;
        $this->dbname = $dbname ?? $this->dbname;

        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        $opt = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $opt);
            $this->dbh->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            die("❌ Errore connessione al DB: " . $e->getMessage());
        }        
    }

    public function query($sql): void {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null): void {
        if ($type === null) {
            $type = match (true) {
                is_int($value)   => PDO::PARAM_INT,
                is_bool($value)  => PDO::PARAM_BOOL,
                is_null($value)  => PDO::PARAM_NULL,
                default          => PDO::PARAM_STR
            };
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute($data = null): bool {
        try {
            return $this->stmt->execute($data);
        } catch (PDOException $e) {
            die("❌ Errore relativo al PDO: " . $e->getMessage());
        }
    }

    public function resultset(): array {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single(): array|false {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $data): bool {
        $cols = implode(',', array_keys($data));
        $vals = ':' . implode(', :', array_keys($data));
        $this->query("INSERT INTO $table ($cols) VALUES ($vals)");
        foreach ($data as $k => $v) $this->bind(":$k", $v);
        return $this->execute();
    }

    public function find(string $table, string $column, mixed $value): array|false {
        $this->query("SELECT * FROM $table WHERE $column = :value LIMIT 1");
        $this->bind(':value', $value);
        return $this->single();
    }

    public function update(string $table, string $id_field, mixed $id_value, array $data): bool {
        $set = implode(', ', array_map(fn($k) => "$k=:$k", array_keys(array_diff_key($data, [$id_field => true]))));
        $this->query("UPDATE $table SET $set WHERE `$id_field` = :$id_field");
        foreach ($data as $k => $v) if ($k !== $id_field) $this->bind(":$k", $v);
        $this->bind(":$id_field", $id_value);
        return $this->execute();
    }

    public function delete(string $table, string $id_field, mixed $id_value): bool {
        $this->query("DELETE FROM $table WHERE $id_field = :id");
        $this->bind(":id", $id_value);
        return $this->execute();
    }
}
