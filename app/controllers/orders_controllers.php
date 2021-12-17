<?php
class orders_controllers{
    public function index($params){
        global $db;
        $customerId = $params["customerId"];
        if(other_helpers::isNotNull($customerId)){
            $orders = $db->read("select * from orders where customerId = ?", $customerId);
        }else{
            $orders = $db->read("select * from orders");
        }

        $allOrders = array();
        foreach ($orders as $order){
            $orderItems = $db->read("select * from order_items where orderId = ?", $order["id"]);
            $order["items"] = $orderItems;
            $allOrders[] = $order;
        }
        other_helpers::response(["status" => 200, "orders" => $allOrders]);
    }

    public function delete($params){
        $id = $params["orderId"] ?? null;
        $response = array("status" => 400, "message" => "please fill in all the required fields");
        if (other_helpers::isNotNull($id)){
            global $db;
            $delete = $db->delete("orders", $id);
            if ($delete !== false){
                $deleteItems = $db->delete("order_items", $id, "orderId");
                if ($deleteItems !== false){
                    $response["itemsDeleted"] = "successful";
                }else{
                    $response["itemsDeleted"] = "unsuccessful";
                }
                $response["status"] = 200;
                $response["message"] = "the deletion was successful";
            }else{
                $response["status"] = 400;
                $response["message"] = "system problem please try again later";
            }
        }
        other_helpers::response($response);
    }

    public function create($params){
        global $db;
        $customerId = $params["customerId"] ?? null;
        $itemsParams = $params["items"] ?? null;
        $response = array("status" => 400, "message" => "please fill in all the required fields");
        if (other_helpers::isNotNull($customerId, $itemsParams)){
            $items = explode(",", $itemsParams);
            $orderItems = array();
            $totalOrder = 0;
            foreach ($items as $item){
                $itemParse = explode("-", $item);
                $itemId = $itemParse[0];
                $quanity = $itemParse[1] ?? 1;
                $getItem = $db->read("select * from products where id = ?", $itemId)[0];
                $stock = $getItem["stock"];
                $stock = $stock - $quanity;
                if ($stock > 0){
                    $updateStock = $db->update("products", "stock", $getItem["id"], $stock);
                    if ($updateStock !== false){
                        $price = $getItem["price"];
                        $total = ($price * $quanity);
                        $totalOrder += $total;
                        $orderItems[] =  array(
                            "status" => true,
                            "productId" => $getItem["id"],
                            "quantity" => $quanity,
                            "unitPrice" => $price,
                            "total" => $total,
                            "category" => $getItem["category"],
                            "price" => $getItem["price"]
                        );
                    }else{
                        $orderItems[] = array(
                            "status" => false,
                            "message" => "stock update failed",
                            "productId" => $getItem["id"],
                            "productName" => $getItem["name"],
                            "category" => $getItem["category"],
                            "price" => $getItem["price"]
                        );
                    }
                }else{
                    $orderItems[] = array(
                        "status" => false,
                        "message" => "product is out of stock",
                        "productId" => $getItem["id"],
                        "productName" => $getItem["name"],
                        "category" => $getItem["category"],
                        "price" => $getItem["price"]
                    );
                }
            }
            $discount = new discount_helpers();
            $discount->total = $totalOrder;
            $discount->customerId = $customerId;
            $discount->items = $orderItems;

            $discounts = $discount->allDiscounts();

            if(count($discounts) > 0){
                $totalOrder = $discount->getDiscountedTotal();
                $response["discounts"] = $discounts;
                $response["totalDiscount"] = $discount->totalDiscount;
            }
            $orderId = $db->create("orders", "customerId,total", $customerId, $totalOrder);
            foreach ($orderItems as $orderItem){
                if ($orderItem["status"]){
                    $db->create(
                        "order_items",
                        "orderId,productId,quantity,unitPrice,total",
                        $orderId, $orderItem["productId"], $orderItem["quantity"], $orderItem["unitPrice"], $orderItem["total"]
                    );
                }else{
                    $response["itemErrors"][] = $orderItem;
                }
            }

            $response["orderId"] = $orderId;
            $response["customerId"] = $customerId;
            $response["status"] = 200;
            $response["message"] = "your order has been created";
            $response["totalPayment"] = $totalOrder;
        }
        other_helpers::response($response);
    }
}
?>