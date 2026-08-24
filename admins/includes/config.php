<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','impulse102');
// Establish database connection.
try
{
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    
    // Auto-create admin table if missing
    $dbh->exec("CREATE TABLE IF NOT EXISTS `admin` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `UserName` varchar(100) NOT NULL,
      `Password` varchar(100) NOT NULL,
      `updationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    // Auto-insert default admin user if empty
    $chkAdmin = $dbh->query("SELECT COUNT(*) FROM `admin`")->fetchColumn();
    if ($chkAdmin == 0) {
        $adminPass = md5('admin');
        $dbh->exec("INSERT INTO `admin` (`id`, `UserName`, `Password`) VALUES (1, 'admin', '$adminPass');");
    }
}
catch (PDOException $e)
{
    exit("Error: " . $e->getMessage());
}
?>
