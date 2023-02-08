<?php
// connection avec pdo + verification erreur 
class Database
{
    
    private static $dbHost = "localhost";
    private static $dbName = "burger-code";
    private static $dbUser = "Lmickael";
    private static $dbUserPassword = "Nathan241121.";

    private static $connection = null;

    public static function connect()
    {   //verification erreur
        try 
        {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,self::$dbUser,self::$dbUserPassword);
        } 
        catch (PDOException $e) 
        {
        die($e->getMessage());
        }
        return self::$connection;
    }

    public static function disconnect()
    
    {
        self::$connection=null;
    }

}

?>