<?php
/**
 * Class Database untuk koneksi ke database
 */

class Database {
    private $host = 'localhost';
    private $username = 'appsbeem_admin';
    private $password = 'A7by777__';
    private $database = 'appsbeem_gereja';
    private $pdo;
    private $lastStatement;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $this->lastStatement = $stmt;
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query error: " . $e->getMessage());
        }
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function execute($sql = null, $params = []) {
        // Jika tidak ada parameter, gunakan statement terakhir
        if ($sql === null) {
            if (isset($this->lastStatement)) {
                return $this->lastStatement->rowCount();
            }
            return 0;
        }
        
        // Jika ada parameter, jalankan query baru
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    // Method untuk kompatibilitas dengan kode lama
    public function resultSet() {
        // Ambil statement terakhir yang dijalankan
        if (isset($this->lastStatement)) {
            return $this->lastStatement->fetchAll();
        }
        return [];
    }
    
    public function single() {
        // Ambil statement terakhir yang dijalankan
        if (isset($this->lastStatement)) {
            return $this->lastStatement->fetch();
        }
        return null;
    }
    
    // Method bind untuk kompatibilitas dengan kode lama
    public function bind($position, $value, $type = null) {
        // Method ini tidak diperlukan untuk PDO, tapi ditambahkan untuk kompatibilitas
        // Parameter akan diproses langsung di method query()
        return $this;
    }
}
?>
