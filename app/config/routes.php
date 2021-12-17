<?php
$route = new route_helpers();
$route->get("orders", "orders_controllers.index");
$route->post("orders", "orders_controllers.create");
$route->delete("orders", "orders_controllers.delete");
?>