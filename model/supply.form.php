<?php
require 'Database.php';

session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $unit = htmlspecialchars($_POST['unit']);
        $product = htmlspecialchars($_POST['product']);
        $qty = htmlspecialchars($_POST['qty']);
        $price = htmlspecialchars($_POST['price']);
        $ExpiryDate = htmlspecialchars($_POST['ExpiryDate']);
        $purchasePrice = htmlspecialchars($_POST['purchasePrice']);

        //update
        // $pack = htmlspecialchars($_POST['pack']);
        // $unitpack = htmlspecialchars($_POST['unitpack']);
        $wholesale = htmlspecialchars($_POST['wholesale']);


        $errors = [];
        $success = [];

        if($unit == '--choose--'){
            $errors['dpt'] = 'Department is required!';
        }

        if(empty(trim($product))){
            $errors['product'] = 'Product is required!';
        }

        if(empty(trim($price))){
            $errors['price'] = 'Price is required!';
        } elseif (floatval($price) < 0) {
            $errors['price_'] = 'Price cannot be less than 0';
        }

        if(empty(trim($qty))){
            $errors['qty'] = 'Quantity is required!';
        } elseif (floatval($qty) < 0){
            $errors['qty'] = 'Quantity cannot be less than 0';
        }

        $stmtpro = $db->checkExist('SELECT `SupplyID`, `Quantity`, `Department`, `ProductName`, `ExpiryDate` FROM `supply_tbl` WHERE `ExpiryDate` = :ExpiryDate AND `Department` = :Department AND `ProductName` = :ProductName', [
            ':Department' => $unit,
            ':ProductName' => $product,
            ':ExpiryDate' => $ExpiryDate
        ]);
        $productExist = $stmtpro->fetch(PDO::FETCH_ASSOC);

        if(empty($errors)){
            if($productExist > 0){
                $newQty = $productExist['Quantity'] + $qty;
                $SupplyID = $productExist['SupplyID'];
                $pro = $productExist['ProductName'];

                $stmtupdate = $db->conn->prepare("UPDATE supply_tbl SET Quantity = :newQty, Price = :price WHERE ProductName = :product");
                $result = $stmtupdate->execute([
                    ':newQty' => $newQty,
                    ':price' => $price,
                    ':product' => $pro
                ]);

                if($result){
                    $success['message'] = 'Product stored successfully!';
                }
            } else {
                $user = $_SESSION['email'];
                // $qty =  $unitpack * $pack;
                $stmt = $db->conn->prepare("INSERT INTO `supply_tbl` (`Department`, `ProductName`, `Quantity`, `Price`, `Pprice`, `ExpiryDate`, `RecordedBy`, `wholesaleprice`) VALUES (:dpt, :product, :qty , :price, :Pprice, :ExpiryDate, :RecordedBy, :wholesale) ");
                $stmt->bindParam(':dpt', $unit, PDO::PARAM_INT);
                $stmt->bindParam(':product', $product, PDO::PARAM_STR);
                $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':Pprice', $purchasePrice);
                $stmt->bindParam(':ExpiryDate', $ExpiryDate);
                $stmt->bindParam(':RecordedBy', $user);
                //updates
                // $stmt->bindParam(':pack', $pack);
                // $stmt->bindParam(':unitpack', $unitpack);
                $stmt->bindParam(':wholesale', $wholesale);
                $result = $stmt->execute();

                if($result){
                    $success['message'] = 'Product stored successfully!';
                }
            }

            echo json_encode(['status' => true, 'message' => $success]);
        } else {
            echo json_encode(['status' => false, 'errors' => $errors]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['status' => false, 'errors' => 'Internal Server Error']);
    }
}
?>
