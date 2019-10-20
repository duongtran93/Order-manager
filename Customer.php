<?php


class Customer
{
    public $customerName;
    public $phone;

    public function __construct($customerName, $phone)
    {
        $this->customerName = $customerName;
        $this->phone = $phone;
    }
}