<?php
class Database extends PDO
{
    public function __construct()
    {
        $dsn = "mysql:host=https://s054ns1.hostinghispeed.com/;dbname=rspgkppp_dbnew;charset=utf8mb4";
        // parent::__construct($dsn, 'root', '');
        parent::__construct($dsn, 'rspgkppp_sa', 'Merlin5525.');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
?>