<?php


class Product
{
    public $productName;
    public $totalProduct;
    public $productPrice;

    public function __construct($productName, $totalProduct, $productPrice)
    {
        $this->productName = $productName;
        $this->totalProduct = $totalProduct;
        $this->productPrice = $productPrice;
    }
}