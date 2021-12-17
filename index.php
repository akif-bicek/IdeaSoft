<?php
ob_start();

const config = "app/config/";
const helpers = "helpers/";

require_once config . "database.php";
require_once config . "project.php";

require_once helpers . "mysql_helpers.php";
require_once helpers . "request_helpers.php";
require_once helpers . "route_helpers.php";
require_once helpers . "other_helpers.php";
require_once helpers . "discount_helpers.php";

$db = new mysql_helpers();

foreach (glob("app/controllers/*.php") as $filename)
{
    require_once $filename;
}

require_once config . "routes.php";
ob_end_flush();
?>