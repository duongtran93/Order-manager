<?php
include_once 'DBConnect.php';
include_once 'Order.php';
include_once 'OrderManager.php';
include_once 'Product.php';
include_once 'Customer.php';
include_once 'StatusConstant.php';

$orderManager = new OrderManager();
$id = $_GET['id'];
$order = $orderManager->getOrderById($id);
$products = $orderManager->getProductById($id);
$customer = $orderManager->getCustomerById($id);

if (isset($_POST['status'])) {
    $newStatus = $_POST['status'];
    $orderManager->updateStatus($id, $newStatus);
    header("Location:orderDetail.php?id=$id");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Thông tin khách hàng</h1>
<table>
    <tr>
        <td>Tên khách hàng: </td>
        <td><?php echo $customer->customerName ?></td>
    </tr>
    <tr>
        <td>Phone: </td>
        <td><?php echo $customer->phone ?></td>
    </tr>
    <tr>
        <td>Ngày mua: </td>
        <td><?php echo $order->orderDate ?></td>
    </tr>
</table>
<label>Tình trạng: </label>
<form action="" method="post">
    <select name="status">
        <option <?php echo ($order->status == StatusConstant::SHIPPED) ? 'selected="selected"' : '';?>>Shipped</option>
        <option <?php echo ($order->status == StatusConstant::CANCELLED) ? 'selected="selected"' : '';?>>Cancelled</option>
        <option <?php echo ($order->status == StatusConstant::IN_PROCESS) ? 'selected="selected"' : '';?>>In Process</option>
        <option <?php echo ($order->status == StatusConstant::ON_HOLD) ? 'selected="selected"' : '';?>>On Hold</option>
        <option <?php echo ($order->status == StatusConstant::RESOLVED) ? 'selected="selected"' : '';?>>Resolved</option>
        <option <?php echo ($order->status == StatusConstant::DISPUTED) ? 'selected="selected"' : '';?>>Disputed</option>
    </select>
    <input type="submit" value="Update" onclick="return confirm('You want modify?')">
</form>

<h1>Chi tiết đơn hàng</h1>

<table border="1">
    <tr>
        <td>STT</td>
        <td>Tên sản phẩm</td>
        <td>Số lượng</td>
        <td>Thành tiền</td>
    </tr>
    <tr>
        <?php foreach ($products as $key => $product): ?>
            <tr>
                <td><?php echo ++$key ?></td>
                <td><?php echo $product->productName ?></td>
                <td><?php echo $product->totalProduct ?></td>
                <td><?php echo $product->productPrice ?></td>
            </tr>
        <?php endforeach; ?>
    </tr>
</table>
</body>
</html>