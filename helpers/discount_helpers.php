<?php
class discount_helpers{
    public $customerId;
    public $items;
    public $total;

    public $totalDiscount;

    function __construct() {
        $this->totalDiscount = 0;
    }

    public function allDiscounts(){
        return $this->discounts(
            $this->oneThousandAbove(),
            $this->category2Taken6(),
            $this->category1Taken2AndMore()
        );
    }

    public function oneThousandAbove(){
        if ($this->total >= 1000){
            $discountAmount = $this->percent($this->total, 10);
            $this->totalDiscount += $discountAmount;
            $subtotal = ($this->total - $this->totalDiscount);
            $message = "One Thousand Above Discount";
            return array(
                "discountReason" => $message,
                "discountAmount" => $discountAmount,
                "subtotal" => $subtotal
            );
        }
    }

    public function category2Taken6(){
        foreach ($this->items as $item){
            if (($item["category"] == 2) and ($item["quantity"] == 6)){
                $productPrice = $item["price"];
                $discountAmount = $productPrice;
                $this->totalDiscount += $discountAmount;
                $subtotal = ($this->total - $this->totalDiscount);
                $message = "6 were taken of category 2";
                return array(
                    "discountReason" => $message,
                    "discountAmount" => $discountAmount,
                    "subtotal" => $subtotal
                );
            }
        }
    }

    public function category1Taken2AndMore(){
        foreach ($this->items as $item){
            if ($this->categoryQuantity(2) > 0){
                $discountAmount = $this->percent($this->minPrice(), 20);
                $this->totalDiscount += $discountAmount;
                $subtotal = ($this->total - $this->totalDiscount);
                $message = "2 and more were taken of category 1";
                return array(
                    "discountReason" => $message,
                    "discountAmount" => $discountAmount,
                    "subtotal" => $subtotal
                );
            }
        }
    }

    public function getDiscountedTotal(){
        return ($this->total - $this->totalDiscount);
    }

    private function percent($value, $percent){
        return ($value * $percent) / 100;
    }

    private function minPrice(){
        $prices = array();
        foreach ($this->items as $item){
            $prices[] = $item["price"];
        }
        return min($prices);
    }

    private function discounts(){
        $discounts = array();
        $args = func_get_args();
        foreach ($args as $arg){
            if (!empty($arg)){
                $discounts[] = $arg;
            }
        }
        return $discounts;
    }

    private function categoryQuantity($categoryId){
        $count = 0;
        foreach ($this->items as $item){
            if ($item["category"] == $categoryId){
                $count++;
            }
        }
        return $count;
    }
}
?>