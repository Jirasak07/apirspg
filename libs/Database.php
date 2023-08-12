<?php
class Database extends PDO
{
    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=rspgkppp_db;charset=utf8mb4";
        parent::__construct($dsn, 'root', '');
        // parent::__construct($dsn, 'rspgkppp_jirasak', 'Merlin5409.');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
?>