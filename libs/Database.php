<?php
class Database extends PDO
{
    public function __construct()
    {
        // $dsn = "mysql:host=27.254.152.23;dbname=rspgkppp_dbnew;charset=utf8mb4";
        $dsn = "mysql:host=localhost;dbname=rspgkppp_dbnew;charset=utf8mb4";
        // parent::__construct($dsn, 'root', '');
        // parent::__construct($dsn, 'rspgkppp_sa', 'Merlin5525.');
        parent::__construct($dsn, 'root', '');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
?>