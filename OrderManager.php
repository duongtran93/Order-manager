<?php
include_once 'DBConnect.php';
include_once 'Order.php';
include_once 'Product.php';
include_once 'Customer.php';


class OrderManager
{
    protected $conn;

    public function __construct()
    {
        $db = new DBConnect('mysql:host=localhost;dbname=classicmodels', 'root', 'ngocduong93');
        $this->conn = $db->connect();
    }

    public function getAll()
    {
        $sql = "SELECT o.orderNumber AS 'orderNumber', o.orderDate AS 'orderDate',
                o.status AS 'status', SUM(od.priceEach) AS 'totalPrice' 
                FROM orders o
                INNER JOIN orderdetails od
                ON o.orderNumber = od.orderNumber
                GROUP BY od.orderNumber";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetchAll();
        $orders = [];
        foreach ($result as $value) {
            $order = new Order($value['orderNumber'], $value['orderDate'], $value['status'], $value['totalPrice']);
            array_push($orders, $order);
        }
        return $orders;

    }

    public function getOrderById($id)
    {
        $sql = "SELECT o.orderNumber AS 'orderNumber', o.orderDate AS 'orderDate',
                o.status AS 'status', SUM(od.priceEach) AS 'totalPrice' 
                FROM orders o
                INNER JOIN orderdetails od
                ON o.orderNumber = od.orderNumber
                WHERE o.orderNumber =:id
                GROUP BY od.orderNumber";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        $orderNumber = $result['orderNumber'];
        $orderDate = $result['orderDate'];
        $status = $result['status'];
        $totalPrice = $result['totalPrice'];
        $order = new Order($orderNumber, $orderDate, $status, $totalPrice);
        return $order;
    }

    public function getProductById($id)
    {
        $sql = "SELECT p.productName AS 'productName', COUNT(od.productCode) AS 'totalProduct',
                SUM(p.buyPrice) AS 'productPrice'
                FROM products p
                INNER JOIN orderdetails od
                ON p.productCode = od.productCode
                WHERE od.orderNumber =:id
                GROUP BY p.productCode";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $products = [];
        foreach ($result as $value) {
            $product = new Product($value['productName'], $value['totalProduct'], $value['productPrice']);
            array_push($products, $product);
        }
        return $products;
    }

    public function getCustomerById($id)
    {
        $sql = "SELECT c.customerName AS 'customerName', c.phone AS 'phone'
                FROM customers c
                INNER JOIN orders o 
                ON o.customerNumber = c.customerNumber
                WHERE o.orderNumber =:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        $customerName = $result['customerName'];
        $phone = $result['phone'];
        $customer = new Customer($customerName, $phone);
        return $customer;
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE orders SET status =:status WHERE orderNumber=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

}