<?php
/**
 * AgroNGO PHP PDO ORM & Active Record Engine
 */

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
            $db   = defined('DB_NAME') ? DB_NAME : 'impulse102';
            $user = defined('DB_USER') ? DB_USER : 'root';
            $pass = defined('DB_PASS') ? DB_PASS : '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("ORM Connection Error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

abstract class Model {
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected array $attributes = [];

    public function __construct(array $attributes = []) {
        $this->attributes = $attributes;
    }

    public function __get(string $name) {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void {
        $this->attributes[$name] = $value;
    }

    public static function find($id): ?static {
        $pdo = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;

        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$pk` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? new static($row) : null;
    }

    public static function where(string $field, $value): array {
        $pdo = Database::getConnection();
        $table = static::$table;

        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$field` = :val");
        $stmt->execute(['val' => $value]);
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new static($row), $rows);
    }

    public static function all(): array {
        $pdo = Database::getConnection();
        $table = static::$table;

        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new static($row), $rows);
    }

    public static function create(array $data): ?static {
        $pdo = Database::getConnection();
        $table = static::$table;

        $fields = array_keys($data);
        $placeholders = array_map(fn($f) => ":$f", $fields);

        $sql = "INSERT INTO `$table` (`" . implode("`, `", $fields) . "`) VALUES (" . implode(", ", $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $lastId = $pdo->lastInsertId();
        if ($lastId) {
            return static::find($lastId);
        }
        return new static($data);
    }

    public function save(): bool {
        $pdo = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;

        if (isset($this->attributes[$pk])) {
            $id = $this->attributes[$pk];
            $fields = [];
            $params = ['pk_id' => $id];

            foreach ($this->attributes as $key => $val) {
                if ($key === $pk) continue;
                $fields[] = "`$key` = :$key";
                $params[$key] = $val;
            }

            $sql = "UPDATE `$table` SET " . implode(", ", $fields) . " WHERE `$pk` = :pk_id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } else {
            $created = static::create($this->attributes);
            if ($created) {
                $this->attributes = $created->attributes;
                return true;
            }
            return false;
        }
    }

    public function delete(): bool {
        $pdo = Database::getConnection();
        $table = static::$table;
        $pk = static::$primaryKey;

        if (!isset($this->attributes[$pk])) return false;

        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$pk` = :id");
        return $stmt->execute(['id' => $this->attributes[$pk]]);
    }
}
