<?php
  require 'Database.php';
  require 'vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tCode'])) {
    $tCode = htmlspecialchars($_POST['tCode']);
    $cash = $_POST['cash'] ?? 0;
    $transfer = $_POST['transfer'] ?? 0;
    $pos = $_POST['pos'] ?? 0;

    try{
      $db->conn->beginTransaction();
      // 1. Update transaction status
      $stmt = $db->conn->prepare('
        UPDATE transaction_tbl 
        SET `Status` = "Credit" 
        WHERE tCode = :tCode
      ');
      $stmt->execute([':tCode' => $tCode]);

      // 2. Fetch transaction details
      $stmtFetch = $db->conn->prepare('
        SELECT Product, tDepartment, qty, transaction_tbl.Price AS Price, Credit, 
          customers_tbl.Fullname AS customer_name, 
          customers_tbl.email AS customer_email,
          customers_tbl.phone AS customer_phone,
          supply_tbl.ProductName AS product_name
        FROM transaction_tbl 
        JOIN customers_tbl ON transaction_tbl.cid = customers_tbl.id
        JOIN supply_tbl ON transaction_tbl.Product = supply_tbl.SupplyID
        WHERE tCode = :tCode
      ');
      $stmtFetch->execute([':tCode' => $tCode]);
      $transactions = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);

      if(!$transactions) {
        throw new Exception('No transaction records found.');
      }
        // 3. Prepare totals and body content
        $totalAmount = 0;
        $emailBodyItems = "<ol>";
        $smsBody = "Dear " . $transactions[0]['customer_name'] . ",\nYou purchased the following on credit:\n";

        foreach ($transactions as $i => $tx) {
          $totalAmount += $tx['Credit'];
          $emailBodyItems .= "<li>{$tx['product_name']} (Qty: {$tx['qty']} | Price: " . number_format($tx['Price']) . " | Amount: " . number_format($tx['Credit']) . ")</li>";
          $smsBody .= ($i + 1) . ". {$tx['product_name']} (Qty: {$tx['qty']}, Amt: ₦" . number_format($tx['Credit']) . ")\n";
        }

        $emailBodyItems .= "</ol>";
        $smsBody .= "Total: ₦" . number_format($totalAmount, 2) . "\nPlease pay soon. Thank you.";

        $customerName = $transactions[0]['customer_name'];
        $customerEmail = $transactions[0]['customer_email'];
        $customerPhone = $transactions[0]['customer_phone'];

        // 4. Update stock
        foreach ($transactions as $tx) {
          $stmtQty = $db->conn->prepare('
            SELECT Quantity 
            FROM supply_tbl 
            WHERE Department = :department AND SupplyID = :product
          ');
          $stmtQty->execute([
            ':department' => $tx['tDepartment'],
            ':product' => $tx['Product']
          ]);
          $supply = $stmtQty->fetch(PDO::FETCH_ASSOC);

          if(!$supply) {
            throw new Exception('Stock not found for product ID: ' . $tx['Product']);
          }

          $newQty = $supply['Quantity'] - $tx['qty'];
          if($newQty < 0) {
            throw new Exception("Insufficient stock for product ID: " . $tx['Product']);
          }

          $stmtUpdate = $db->conn->prepare('
            UPDATE supply_tbl 
            SET Quantity = :newQty 
            WHERE SupplyID = :product AND Department = :department
          ');
          $stmtUpdate->execute([
            ':newQty' => $newQty,
            ':product' => $tx['Product'],
            ':department' => $tx['tDepartment']
          ]);
        }

        // 5. Send Email
        $mail = new PHPMailer(true);
        try{
          $mail->isSMTP();
          $mail->Host = 'sfge.org.ng';
          $mail->SMTPAuth = true;
          $mail->Username = 'info.shafafertilizer@sfge.org.ng';
          $mail->Password = '*H@mxah4u#';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port = 465;

          $mail->setFrom('info.shafafertilizer@sfge.org.ng', 'Shafa Global Fertilizer - Admin');
          $mail->addAddress($customerEmail, $customerName);
          $mail->isHTML(true);
          $mail->Subject = 'Credit Transaction Details';

          $mail->Body = "
            <p>Dear {$customerName}, {$customerPhone} We would like to inform you that you have purchased the following items on credit:</p>
            {$emailBodyItems}
            <p>Total Amount: &#x20A6;" . number_format($totalAmount, 2) . "</p>
            <p>Please pay soon. Thank you!</p>
          ";

          $mail->send();
        }catch (Exception $e) {
          error_log("Email failed: " . $mail->ErrorInfo);
        }

        // 6. Send SMS using Termii
        $curl = curl_init();
        $termiiData = array(
          "api_key" => "API",//"TLYXHvzuCdcYcfLDyFGYLzqjwoYAtnexWveYkIIDGSTNosvvGcNteRbJPNeZeB", 
          //"to" => 2348037856962,//$customerPhone, "to" => preg_replace('/^0/', '234', $customerPhone),
          "from" => "SHAFA FGE",
          "sms" => $smsBody,
          "type" => "plain",
          "channel" => "generic"
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($termiiData),
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
        ));

        $smsResponse = curl_exec($curl);
        curl_close($curl);

        // Commit changes
        $db->conn->commit();

        echo json_encode([
            'status' => true,
            'message' => 'Transaction validated, stock updated, email and SMS sent.',
            'sms_response' => json_decode($smsResponse, true)
        ]);
    }catch (Exception $e) {
      $db->conn->rollBack();
      echo json_encode([
        'status' => false,
        'errors' => ['error' => $e->getMessage()]
      ]);
    }
  }else{
    echo json_encode([
      'status' => false,
      'message' => 'Invalid request.'
    ]);
  }
?>