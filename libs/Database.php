<?php
class Database extends PDO
{
    public function __construct()
    {
 $dsn = "mysql:host=https://s054ns1.hostinghispeed.com/;dbname=rspgkppp_dbnew;charset=utf8mb4";
        // $dsn = "mysql:host=localhost;dbname=rspgkppp_dbnew;charset=utf8mb4";
        // parent::__construct($dsn, 'rspgkppp_sa', 'Merlin5525.');
        parent::__construct($dsn, 'root', '');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
        // $dsn = "mysql:host=https://s054ns1.hostinghispeed.com/;dbname=rspgkppp_dbnew;charset=utf8mb4";