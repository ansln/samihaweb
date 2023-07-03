<?php

require_once '../auth/user/conn.php';
require_once '../../../auth/comp/vendor/autoload.php';

class transactionManagement{

    private function getDb(){
        $get = new connection;
        $db = $get->getDb();
        return $db;
    }

    private function sanitize($value){
        $db = $this->getDb();
        $getSanitize = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $dbSanitize = $db->real_escape_string($getSanitize);
        return $dbSanitize;
    }

    public function getCurrentTime(){
        date_default_timezone_set("Asia/Jakarta");
        $time = date("d M, Y H:i");
        return $time;
    }
    
    private function allPaymentData($invoiceId){
        $url = "https://api.midtrans.com/v2/status";
        $getOrder = $this->sanitize($invoiceId);
        $urlForStatusTx = "https://api.midtrans.com/v2/$getOrder/status";
        $str = "Mid-server-CBBdLp5fmuQ-o88qGxHd2_vT";
        $token = base64_encode($str);

        //CURL CITY
        $midtransCurl = curl_init();
        curl_setopt_array($midtransCurl, array(
            CURLOPT_URL => $urlForStatusTx,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: $token"
            )
        ));

        $loadCurl = curl_exec($midtransCurl);

        curl_close($midtransCurl);
        $curlDecode = json_decode($loadCurl, true);
        return $curlDecode;
    }

    private function invoiceQuery(){
        $db = $this->getDb();
        $allData = array();
        $invoiceQuery = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceStatus = 'done'");
        $invoiceQueryCheck = mysqli_num_rows($invoiceQuery);
        if ($invoiceQueryCheck <= 0) {
            echo "belum ada transaksi";
        }else{

            foreach ($invoiceQuery as $invoice) {

                $invoiceId = $invoice["invoiceId"];
                $invoiceTime = $invoice["invoiceTime"];
                $userId = $invoice["userId"];
                $userShipping = $invoice["userShipping"];
                $userShippingEst = $invoice["userShippingEst"];
                $userShippingPrice = $invoice["userShippingPrice"];
                $totalProductPrice = $invoice["totalProductPrice"];
                $totalProductWeight = $invoice["totalProductWeight"];
                $itemTemp = array();
                $orderTemp = array();
                $paymentData = $this->allPaymentData($invoiceId);

                $data = array(
                    'invoiceId' => $invoiceId,
                    'invoiceTime' => $invoiceTime,
                    'userId' => $userId,
                    'userShipping' => $userShipping,
                    'userShippingEst' => $userShippingEst,
                    'userShippingPrice' => $userShippingPrice,
                    'totalProductPrice' => $totalProductPrice,
                    'totalProductWeight' => $totalProductWeight
                );

                $invoiceItemQuery = mysqli_query($db, "SELECT * FROM invoice_item WHERE invoiceId = '$invoiceId'");
                foreach ($invoiceItemQuery as $invoiceItem) {
                    $productId = $invoiceItem["productId"];
                    $productQty = $invoiceItem["productQty"];
    
                    $dataItem = array(
                        'productId' => $productId,
                        'productQty' => $productQty
                    );
                    array_push($itemTemp, $dataItem);
                }

                $orderStatusQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$invoiceId'");
                foreach ($orderStatusQuery as $order) {
                    $orderStatus = $order["status"];
                    $lastUpdate = $order["lastUpdate"];
    
                    $orderItem = array(
                        'status' => $orderStatus,
                        'last_update_data' => $lastUpdate
                    );
                    array_push($orderTemp, $orderItem);
                }

                $allDataGet = array(
                    'invoice' => $data,
                    'invoice_item' => $itemTemp,
                    'payment' => $paymentData,
                    'order_status' => $orderTemp
                );

                array_push($allData, $allDataGet);
            }

            return $allData;
        }
    }

    private function paymentStatus($status){
        if ($status == 'settlement') {
            ?><i class="fa-solid fa-circle data-status-1"></i><div id="payStat">Pembayaran Lunas</div><?php
        }elseif ($status == 'pending') {
            ?><i class="fa-solid fa-circle data-status-2"></i><div id="payStat">Menunggu Pembayaran</div><?php
        }elseif ($status == 'cancel') {
            ?><i class="fa-solid fa-circle data-status-3"></i><div id="payStat">Pembayaran Dibatalkan</div><?php
        }elseif ($status == 'expire') {
            ?><i class="fa-solid fa-circle data-status-3"></i><div id="payStat">Pembayaran Expired</div><?php
        }
    }

    private function statusInfo($status){
        if ($status == 'menunggu pembayaran') {
            echo "Menunggu Pembayaran";
        }elseif ($status == 'menunggu konfirmasi') {
            echo "Menunggu Konfirmasi";
        }elseif ($status == 'diproses') {
            echo "Diproses";
        }elseif ($status == 'dikirim') {
            echo "Dikirim";
        }elseif ($status == 'selesai') {
            echo "Selesai";
        }
    }

    private function getUserData($user){
        $db = $this->getDb();
        $userQuery = mysqli_query($db, "SELECT * FROM user WHERE id = '$user'");
        foreach ($userQuery as $user) {
            $firstName = $user["u_fName"];
            $lastName = $user["u_lName"];
            $fullName = $firstName . " " . $lastName;
            return $fullName;
        }
    }

    private function getProduct($product){
        $db = $this->getDb();
        $temp = array();
        $productQuery = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$product'");
        foreach ($productQuery as $product) {
            $productName = $product["pd_name"];
            array_push($temp, $productName);
        }
        return $temp;
    }

    private function fetchData(){
        error_reporting(0);
        $getData = $this->invoiceQuery();
        rsort($getData);
        
        // echo json_encode($getData);
        
        ?>
        <div class="box-card-ct">
            <div id="top-card-title">Daftar Transaksi</div>
            <?php foreach ($getData as $orderData) {
                $invoice = $orderData["invoice"];
                $invoiceId = $invoice["invoiceId"];
                $invoiceItem = $orderData["invoice_item"];
                $payment = $orderData["payment"];
                $orderStatus = $orderData["order_status"][0]["status"];
                $shippingPrice = $orderData["invoice"]["userShippingPrice"];
                $productPrice = $orderData["invoice"]["totalProductPrice"];
                $allTotalPrice = $shippingPrice + $productPrice;
                $paymentStatus = $payment["transaction_status"];
                $userId = $invoice["userId"];
                $totalPrice = "Rp" . number_format($payment["gross_amount"],0,"",".");
                $lastUpdate = $orderData["order_status"][0]["last_update_data"];
            ?>
            <div class="card-order">
                <div class="top-sec">
                    <div class="co-sec1">
                        <div id="invId"><?= $invoiceId ?></div>
                        <div id="invDate"><?= $invoice["invoiceTime"] ?></div>
                    </div>
                    <div class="co-sec2">
                        <?= $this->paymentStatus($paymentStatus); ?>
                    </div>
                    <div class="co-sec3">
                        <div id="orderStatusTitle">Status</div>
                        <div id="orderStatus"><?= $this->statusInfo($orderStatus); ?></div>
                    </div>
                    <div class="co-sec4">
                        <div id="priceTotalTitle">Total Pembayaran</div>
                        <div id="priceTotal"><?= "Rp" . number_format($allTotalPrice,0,"","."); ?></div>
                    </div>
                    <div class="co-sec5">
                        <form action="" method="POST">
                            <input type="hidden" value="<?= $invoiceId ?>" name="order-data">
                            <button name="detailBtnSubmit" id="detailBtn">Lihat Detail/Ubah Status</button>
                        </form>
                    </div>
                </div>
                <div class="bottom-sec">
                    <div class="cust-sec">
                        <div id="custTitle">Customer</div>
                        <div id="custName"><?= $this->getUserData($userId); ?></div>
                    </div>
                    <div class="update-sec">
                        <div id="lastUpdate">Terakhir Diupdate <span><?= $lastUpdate ?></span></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
        <?php
    }

    private function editOrderStatus($data){
        $db = $this->getDb();
        $invoiceId = $this->sanitize($data);
        $paymentData = $this->allPaymentData($invoiceId);

        // echo json_encode($paymentData);
        error_reporting(0);

        //order data
        $transactionTime = $paymentData["transaction_time"];
        $transactionStatus = $paymentData["transaction_status"];
        $SettlementTime = $paymentData["settlement_time"];
        $paymentType = $paymentData["payment_type"];
        $totalPayment = $paymentData["gross_amount"];

        if ($transactionStatus == "pending") {
            $transactionStatus = "menunggu pembayaran";
        }elseif ($transactionStatus == "settlement") {
            $transactionStatus = "pembayaran lunas";
        }else{
            $transactionStatus = "gagal";
        }

        if ($paymentType == "bank_transfer") {
            $bankType = $paymentData["va_numbers"][0]["bank"];
            $bankName = strtoupper($bankType);
            $bankVA = $paymentData["va_numbers"][0]["va_number"];
            $paymentType = $bankName . "Virtual Account";
        }

        if ($SettlementTime == "") {
            $SettlementTime = "pembayaran tidak berhasil";
        }

        $payData = array(
            'transaction_status' => $transactionTime,
            'transaction_time' => $transactionTime,
            'settlement_time' => $SettlementTime,
            'payment_type' => $paymentType,
            'total_payment' => $totalPayment
        );

        //invoice data, shipping data, etc
        $invoiceFetchQuery = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceId = '$invoiceId'");
        foreach ($invoiceFetchQuery as $inv) {
            $invoiceTime = $inv["invoiceTime"];
            $userId = $inv["userId"];
            $userFullName = $this->getUserData($userId);
            $shipping = $inv["userShipping"];
            $shippingEstimation = $inv["userShippingEst"];
            $shippingPrice = $inv["userShippingPrice"];
            $totalProductPrice = $inv["totalProductPrice"];
            $totalProductWeight = $inv["totalProductWeight"];

            $invData = array(
                'invoice_time' => $invoiceTime,
                'full_name' => $userFullName,
                'shipping' => $shipping,
                'shipping_est' => $shippingEstimation,
                'shipping_price' => $shippingPrice,
                'total_product_price' => $totalProductPrice,
                'total_product_weight' => $totalProductWeight
            );
        }

        //product detail
        $invoiceItemQuery = mysqli_query($db, "SELECT * FROM invoice_item WHERE invoiceId = '$invoiceId'");
        $productTemp = array();
        $detailResi = '';
        foreach ($invoiceItemQuery as $invItem) {
            $productId = $invItem["productId"];
            $productQty = $invItem["productQty"];
            
            $productFetchQuery = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$productId'");
            foreach ($productFetchQuery as $product) {
                $productName = $product["pd_name"];
            }

            $productData = array(
                'product_name' => $productName,
                'product_qty' => $productQty
            );
            array_push($productTemp, $productData);
        }

        //order status
        $orderDataQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$invoiceId'");
        foreach ($orderDataQuery as $oData) {
            $getStatus = $oData["status"];
            $detailResi = $oData["noResi"];
        }

        $allData = array(
            'invoice_data' => $invData,
            'payment_data' => $payData,
            'invoice_item' => $productTemp
        );

        $customerName = $allData["invoice_data"]["full_name"];
        $shippingType = $allData["invoice_data"]["shipping"];
        $shippingEst = $allData["invoice_data"]["shipping_est"];
        $getShippingPrice = $allData["invoice_data"]["shipping_price"];
        $getTotalProductPrice = $allData["invoice_data"]["total_product_price"];
        $grossAmount = $getShippingPrice + $getTotalProductPrice;
        $allProduct = $allData["invoice_item"];
        $allTotalPayment = $allData["payment_data"]["total_payment"];

        ?>
        <form action="" method="post" enctype="multipart/form-data">
        <div class="main-ct-order">
            <div class="top-order-sec">
                <div class="row">
                    <span><b>InvoiceId: </b></span>
                    <span><?= $invoiceId ?></span>
                </div>
                <div class="row">
                    <span><b>Customer Name: </b></span>
                    <span><?= $customerName ?></span>
                </div>
            </div>
            <div class="mid-order-sec">
                <div class="product-detail-sec">
                    <div id="productDetailTitle">Detail Produk Pembelian</div>
                    <div class="productName">
                        <?php foreach ($allProduct as $pd) { ?><div id="pd-name"><?= $pd["product_qty"]; ?>x <?= $pd["product_name"]; ?></div><?php } ?>
                    </div>
                </div>
                <div class="order-sec2">
                    <div id="currentStatus">Status saat ini: <small><b><?= $getStatus ?></b></small></div>
                    <div class="shipping-sec">
                        <div id="shippingTitle">Jenis Pengiriman</div>
                        <div class="column-sec">
                            <div id="shippingName"><?= $shippingType ?></div>
                            <div id="shippingEst"><?= $shippingEst ?></div>
                            <div id="shippingPrice">Rp<?= number_format($getShippingPrice,0,"",".") ?></div>
                        </div>
                    </div>
                    <div class="row-resi">
                        <div id="resi-title">Input No. Resi: </div>
                        <div id="resi-input-text"><input type="text" value="<?= $detailResi ?>" name="resiInput" id="resiInputStyle"></div>
                    </div>
                </div>
                <div class="total-price-sec"><span><b>Total Pembayaran: </b></span>Rp<?= number_format($grossAmount,0,"","."); ?></div> 
                
            </div>
            <div class="bottom-order-sec">
                <div class="row">
                    <span><b>Ubah status: </b></span>
                    <select name="order_status_edit" id="status-select">
                        <option name="select-active" value="diproses" selected>Diproses</option>
                        <option name="select-deactive" value="dikirim">Dikirim</option>
                        <option name="select-deactive" value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <button id="changeStatusBtn" name="changeStatusData">Ubah Status</button>
            <a href="../">Batal</a>
        </div>
    </form>
        <?php
        if (isset($_POST["changeStatusData"])) {
            $NoResi = $_POST["resiInput"];
            $status = $_POST["order_status_edit"];
            $currentTime = $this->getCurrentTime();

            $updateOrderData = "UPDATE order_status SET noResi = '$NoResi', status = '$status', lastUpdate = '$currentTime' WHERE invoiceId = '$invoiceId'";
            mysqli_query($db, $updateOrderData);
            ?><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><style> .swal2-popup { font-size: 14px; }</style><?php
            ?><script type="text/javascript">
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data berhasil di Update',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    showConfirmButton: false
                })
                setTimeout(function(){
                    window.location = "../";
                }, 2000);
            </script><?php
        }
    }

    //setter getter
    public function orderList(){
        return $this->fetchData();
    }
    public function editOrder($data){
        return $this->editOrderStatus($data);
    }
}

?>