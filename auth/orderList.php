<?php

class orderManagement{

    private function getDb(){
        $getDb = new connection;
        $callDb = $getDb->getDb();
        return $callDb;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function split($value){
        $keywords = preg_split("/[\s]+/", $value);
        return $keywords;
    }

    protected function getTime(){
        date_default_timezone_set("Asia/Jakarta");
        $date = date("d M, Y h:i");
        return $date;
    }

    private function getUserEmail(){
        $db = $this->getDb();
        $session = new userSession;
        $userEmail = $session->generateEmail();
        $userEmailSanitize = $this->sanitize($userEmail);
        $userEmailClear = $db->real_escape_string($userEmailSanitize);
        return $userEmailClear;
    }

    private function fetchDb(){
        $db = $this->getDb();
        $userEmail = $this->getUserEmail();
        $realString = $db->real_escape_string($userEmail);

        if (isset($_COOKIE['SMHSESS'])) {
            //userTokenCheck
            $cookie = $_COOKIE['SMHSESS'];
            $userSession = mysqli_query($db, "SELECT * FROM user_session WHERE user_jwt='$cookie'");
            $userSessionCheck = mysqli_num_rows($userSession);
            
            if ($userSessionCheck > 0) {
                //fetch email from user
                $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$realString' OR u_phone = ' $userEmail '");
                $userDataCheck = mysqli_num_rows($userData);
                if ($userDataCheck > 0) {
                    if($userData->num_rows){
                        while($u_fetch = $userData->fetch_object()){
                            include '../layout/navwish.php';
                        }
                    }
                }else{ ?><script>window.location.replace("../logout.php");</script><?php }
            }else{ ?><script>window.location.replace("../logout.php");</script><?php }
        }else{ ?><script>window.location.replace("../logout.php");</script><?php }
    }

    private function getUserId(){
        $db = $this->getDb();
        $get = new userSession;
        $email = $get->generateEmail();
        $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
        foreach ($userData as $user) {
            $userId = $user["id"];
            return $userId; 
        }
    }

    private function getDataFromAPI($orderId){
        $url = "https://api.midtrans.com/v2/status";
        $getOrder = $this->sanitize($orderId);
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

    private function getOrderItem($invoiceId){
        $db = $this->getDb();
        $allItemData = array();
        $getInvoiceItemData = mysqli_query($db, "SELECT * FROM invoice_item WHERE invoiceId = '$invoiceId'");
        foreach ($getInvoiceItemData as $item) {
            $productId = $item["productId"];
            $productQty = $item["productQty"];

            $getProduct = mysqli_query($db, "SELECT * FROM product WHERE pd_id = '$productId'");
            foreach ($getProduct as $product) {
                $productImage = $product["pd_img"];
                $productName = $product["pd_name"];
                $productPrice = $product["pd_price"];

                $productJSON = array(
                    'image' => $productImage,
                    'name' => $productName,
                    'price' => $productPrice,
                    'qty' => $productQty
                );

                array_push($allItemData, $productJSON);
            }
        }
        return $allItemData;
    }

    private function updateOrderStatus($invoiceId){
        $db = $this->getDb();
        $getInvoiceId = $this->sanitize($invoiceId);

        $orderQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$getInvoiceId'");
        $orderQueryCheck = mysqli_num_rows($orderQuery);

        if ($orderQueryCheck <= 0) {
            $insertOrderQuery = "INSERT INTO order_status VALUES(NULL, '$getInvoiceId', '', 'menunggu konfirmasi', '')";
            mysqli_query($db, $insertOrderQuery);
        }
    }

    private function getOrderStatus($invoiceId){
        $db = $this->getDb();
        $orderQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$invoiceId'");
        foreach ($orderQuery as $os) {
            $orderStatus = $os["status"];

            if ($orderStatus == 'menunggu konfirmasi') {
                ?><span id="data-status-1">menunggu konfirmasi</span><?php
            }elseif ($orderStatus == 'diproses') {
                ?><span id="data-status-2">diproses</span><?php
            }elseif ($orderStatus == 'dikirim') {
                ?><span id="data-status-3">dikirim</span><?php
            }elseif ($orderStatus == 'selesai') {
                ?><span>selesai</span><?php
            }
        }
    }

    private function getOrderStatusUser($invoiceId){
        $db = $this->getDb();
        $orderQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$invoiceId'");
        foreach ($orderQuery as $os) {
            $orderStatus = $os["status"];

            if ($orderStatus == 'diproses') {
                ?><a href="?status-result-done=<?= $invoiceId ?>"><button id="doneTransactionBtn">Selesai</button></a><?php
            }elseif ($orderStatus == 'dikirim') {
                ?><a href="?status-result-done=<?= $invoiceId ?>"><button id="doneTransactionBtn">Selesai</button></a><?php
            }elseif ($orderStatus == 'selesai') {
                ?><button>Beri Ulasan</button><?php
            }
        }
    }

    protected function getInvoiceDataQuery($invoiceId){
        $db = $this->getDb();
        $invoiceIdSanitize = $this->sanitize($invoiceId);
        $getInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE invoiceId = '$invoiceId' ORDER BY invoiceIdPK DESC LIMIT 3");
        $getInvoiceDataCheck = mysqli_num_rows($getInvoiceData);
        if ($getInvoiceDataCheck <= 0) {
            echo "belum ada transaksi";
        }else{
            foreach ($getInvoiceData as $userInvoice) {
                $invoiceIdUser = $userInvoice["invoiceId"];
                $getInvoiceId = $userInvoice["invoiceId"];
                $this->updateOrderStatus($getInvoiceId); //update order status when payment is complete
                $invoiceTime = $userInvoice["invoiceTime"];
                $timeSplit = $this->split($invoiceTime);
                $getDate = $timeSplit[0];
                $productPrice = $userInvoice["totalProductPrice"];
                $shippingPrice = $userInvoice["userShippingPrice"];
                $grandTotalPrice = $productPrice + $shippingPrice;

                //get product detail from ~ getOrderItem
                $getProduct = $this->getOrderItem($invoiceIdUser);
                $product = $getProduct[0];
                $productImage = $product["image"];
                $productName = $product["name"];
                $getQty = $product["qty"];
                $getPrice = $product["price"];
                $productQty = $getQty . " barang x " . "Rp" . number_format($getPrice,0,"",".");
                $other = count($getProduct) - 1;

                ?><div class="content-card">
                    <div class="card-child-left">
                        <div class="left-child-sec1">
                            <div id="inv-id"><?= $invoiceIdUser; ?></div>
                            <div id="date"><?= $getDate; ?></div>
                        </div>
                        <div class="left-child-sec2">
                            <img src="<?= $productImage ?>">
                            <div class="text-pd">
                                <h3><?= $productName ?></h3>
                                <p><?= $productQty ?></p>
                                <p><?php if (count($getProduct) > 1) { echo "+" . $other . " produk lainnya"; } ?></p>
                            </div>
                        </div>
                        <div class="left-child-sec3">
                            <?php $this->getOrderStatus($invoiceIdUser); ?>
                            <a href="">Memiliki kendala? hubungi kami</a>
                        </div>
                    </div>
                    <div class="card-child-right">
                        <div class="right-child-sec1">
                            <div class="grand-total">
                                <p>Total Harga</p>
                                <h3>Rp<?= number_format($grandTotalPrice,0,"",".") ?></h3>
                            </div>
                        </div>
                        <div class="right-child-sec2">
                            <div id="tx-detail"><a href="?detail-inv=<?= $invoiceIdUser ?>">Detail Transaksi</a></div>
                            <div id="set-tx-done"><?= $this->getOrderStatusUser($invoiceIdUser); ?></div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        if (isset($_GET["status_true"])) {
            $invId = $_GET["status_true"];
            
            $updateOrderQuery = "UPDATE order_status SET status = 'selesai' WHERE invoiceId = '$invId'";
            ?><script>window.location.replace('./');</script><?php
            mysqli_query($db, $updateOrderQuery);
        }if(isset($_GET["detail-inv"])){
            $get = $_GET["detail-inv"];
            $getInvoice = $db->real_escape_string($get);
            $txUser = new transactionManagementUser;
            ?><div class="modal-container">
                <div class="box-wrap">
                    <?= $txUser->showOrderDetail($getInvoice); ?>
                </div>
            </div>
            <?php
        }
    }

    private function updateStatusDone($invoiceId){
        $db = $this->getDb();
        $invoiceSanitize = $this->sanitize($invoiceId);
        
        ?><script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script><style> .swal2-popup { font-size: 14px; border: none; } .swal2-icon-content{ color: #facea8; }</style>
        <script>
            const link = '?status_true=<?= $invoiceSanitize ?>';
            Swal.fire({
                title: 'Kamu yakin?',
                text: "Pesanan ini akan diselesaikan dan tidak bisa diubah",
                icon: 'warning',
                showDenyButton: true,
                denyButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Selesaikan Pesanan'
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: "Kamu telah menyelesaikan pesanan ini",
                        icon: 'success',
                        showConfirmButton: false
                    })
                    setTimeout(function(){
                        window.location.replace(link);
                    }, 2000);
                }else if (result.isDenied) {
                    window.location.replace('./');
                }
            })
        </script><?php        
    }

    private function invoiceDataHome(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' ORDER BY invoiceIdPK DESC LIMIT 3");
        $getInvoiceDataCheck = mysqli_num_rows($getInvoiceData);
        if ($getInvoiceDataCheck <= 0) {
            echo "belum ada transaksi";
        }else{
            foreach ($getInvoiceData as $userInvoice) {
                
                $invoiceId = $userInvoice["invoiceId"];
                $getDataAPI = $this->getDataFromAPI($invoiceId);
                $paymentStatus = $getDataAPI["transaction_status"];

                if ($paymentStatus != "pending") {
                    $invoiceTime = $userInvoice["invoiceTime"];
                    $timeSplit = $this->split($invoiceTime);
                    $getDate = $timeSplit[0];
                    $productPrice = $userInvoice["totalProductPrice"];
                    $shippingPrice = $userInvoice["userShippingPrice"];
                    $grandTotalPrice = $productPrice + $shippingPrice;
        
                    ?><div class="content-card">
                        <div class="card-child-left">
                            <div class="left-child-sec1">
                                <div id="inv-id"><?= $invoiceId; ?></div>
                                <div id="date"><?= $getDate; ?></div>
                            </div>
                            <div class="left-child-sec2">
                                <img src="https://ik.imagekit.io/samiha/2201e734935dc002df97de25789d4c04-2965287061_xiPNPvyJ3.jpg">
                                <div class="text-pd">
                                    <?php
                                    ?>
                                    <h3>Samiha Kurma Ajwa 500gr</h3>
                                    <p>1 barang x Rp150.000</p>
                                </div>
                            </div>
                            <div class="left-child-sec3">
                                <span>Selesai</span>
                                <a href="">Memiliki kendala? hubungi kami</a>
                            </div>
                        </div>
                        <div class="card-child-right">
                            <div class="right-child-sec1">
                                <div class="grand-total">
                                    <p>Total Harga</p>
                                    <h3>Rp<?= number_format($grandTotalPrice,0,"",".") ?></h3>
                                </div>
                            </div>
                            <div class="right-child-sec2">
                                <a href="">Detail Transaksi</a>
                                <button>Beri Ulasan</button>
                            </div>
                        </div>
                    </div><?php
                }
            }
        }
    }
    
    //setter getter
    public function getNav(){
        return $this->fetchDb();
    }
    
    public function invoiceAllData(){
        return $this->invoiceDataHome();
    }

    protected function getDataAPI($orderId){
        return $this->getDataFromAPI($orderId);
    }

    public function updateStatusOrder($invoiceId){
        return $this->updateStatusDone($invoiceId);
    }
}

class transactionFilter extends orderManagement{

    private function getDb(){
        $getDb = new connection;
        $callDb = $getDb->getDb();
        return $callDb;
    }

    private function sanitize($value){
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function getUserEmail(){
        $db = $this->getDb();
        $session = new userSession;
        $userEmail = $session->generateEmail();
        $userEmailSanitize = $this->sanitize($userEmail);
        $userEmailClear = $db->real_escape_string($userEmailSanitize);
        return $userEmailClear;
    }

    private function getUserId(){
        $db = $this->getDb();
        $get = new userSession;
        $email = $get->generateEmail();
        $userData = mysqli_query($db, "SELECT * FROM user WHERE u_email = '$email' OR u_phone = '$email'");
        foreach ($userData as $user) {
            $userId = $user["id"];
            return $userId; 
        }
    }

    private function getFilter($filter){
        $sanitizeFilter = $this->sanitize($filter);

        if($sanitizeFilter  == "semua"){
            $this->allFilter();
        }if($sanitizeFilter  == "berlangsung"){
            $this->allFilter();
        }if($sanitizeFilter  == "berhasil"){
            $this->successFilter();
        }if($sanitizeFilter == "tidak-berhasil"){
            $this->notsucessFilter();
        }if ($sanitizeFilter == "menunggu-pembayaran") {
            $this->waitingPaymentFilter();
        }
    }

    private function getPaymentDataAPI(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $data = array();
        // error_reporting(0);

        $getAllInvoiceData = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId'");
        
        foreach ($getAllInvoiceData as $invoice) {
            $invoiceId = $invoice["invoiceId"];
    
            $urlForStatusTx = "https://api.midtrans.com/v2/$invoiceId/status";
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
            array_push($data, $curlDecode);
        }
        return $data;
    }

    private function bankPaymentIcon($value){
        $img = '';

        if ($value == "bca") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/BCA_logo_Bank_Central_Asia_QF4K2vpQB.png">';
        }elseif ($value == "bni") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/105-1051729_bank-negara-indonesia-logo-bank-bni-transparan-clipart_SDqTLyrs7.png">';
        }elseif ($value == "bri") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/logo-bank-BRI-baru_237-design_8jkaAC6Ao.png">';
        }elseif ($value == "permata") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Logo_Bank_Permata_GdsDJba2m.png">';
        }elseif ($value == "mandiri") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Mandiri_logo_8qAvZbTm6.png">';
        }

        return $img;
    }

    private function cstorePaymentIcon($value){
        $img = '';

        if ($value == "indomaret") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/indomaret_qJuoW4q_j-.png">';
        }elseif ($value == "alfamart") {
            $img = '<img src="https://ik.imagekit.io/samiha/payment_logo/Logo_Alfamart_2RXEM_ZQw.png">';
        }

        return $img;
    }

    private function updateOrderStatusPending($invoiceId){
        $db = $this->getDb();
        $getInvoiceId = $this->sanitize($invoiceId);

        $orderQuery = mysqli_query($db, "SELECT * FROM order_status WHERE invoiceId = '$getInvoiceId'");
        $orderQueryCheck = mysqli_num_rows($orderQuery);

        if ($orderQueryCheck <= 0) {
            $insertOrderQuery = "INSERT INTO order_status VALUES(NULL, '$getInvoiceId', '', 'menunggu pembayaran', '')";
            mysqli_query($db, $insertOrderQuery);
        }else{
            $updateOrderQuery = "UPDATE order_status SET status = 'menunggu pembayaran' WHERE invoiceId = '$getInvoiceId'";
            mysqli_query($db, $updateOrderQuery);
        }
    }

    private function waitingPaymentFilter(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $getData = $this->getPaymentDataAPI();
        $status = "";
        
        foreach ($getData as $tx) {
            $statusInfo = $tx["status_code"];
            error_reporting(0);
            $status = $tx["transaction_status"];
            $paymentType = $tx["payment_type"];
            
            if ($status == "pending") { //show pending payment

                if ($paymentType == "bank_transfer") { //bank transfer

                    $bankCode = $tx["va_numbers"][0]["bank"];
                    $getBankImage = $this->bankPaymentIcon($bankCode);
                    $orderId = $tx["order_id"];
                    $getPrice = $tx["gross_amount"];
                    $totalPrice = "Rp" . number_format($getPrice,0,"",".");
                    $getTxTime = $tx["transaction_time"];
                    $transactionTime = date('d/m/Y', strtotime($getTxTime));
                    $paymentDueDate = date('d M, H:i', strtotime("+1 day", strtotime($getTxTime)));
                    $getPaymentType = $tx["va_numbers"][0]["bank"];
                    $getPaymentNumber = $tx["va_numbers"][0]["va_number"];
                    $paymentType = strtoupper($getPaymentType) . " " . "Virtual Account";
                    $this->updateOrderStatusPending($orderId);

                    ?><div class="content-card-wp">
                        <div class="card-child-left-wp">
                            <div class="left-child-sec1-wp">
                                <div id="inv-id-wp"><?= $orderId ?></div>
                                <div id="date-wp"><?= $transactionTime ?></div>
                            </div>
                            <div class="left-child-sec2-wp">
                                <div id="child-img"><?= $getBankImage ?></div>
                                <div class="text-sec-wp">
                                    <div class="text-pd-wp">
                                        <p>Metode Pembayaran</p>
                                        <h3><?= $paymentType ?></h3>
                                    </div>
                                    <div class="text-pd-wp">
                                        <p>Nomor Virtual Account</p>
                                        <h3><?= $getPaymentNumber ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="left-child-sec3-wp">
                                <button>Detail Pembelian</button>
                            </div>
                        </div>
                        <div class="card-child-right-wp">
                            <div class="right-child-sec1-wp">
                                <p>Bayar sebelum</p>
                                <span><?= $paymentDueDate ?></span>
                            </div>
                            <div class="right-child-sec2-wp">
                                <div class="grand-total-wp">
                                    <p>Total Pembelian</p>
                                    <h3><?= $totalPrice ?></h3>
                                </div>
                            </div>
                        </div>
                    </div><?php
                }elseif ($paymentType == "cstore") { //indomaret, alfamart, etc

                    $orderId = $tx["order_id"];
                    $storeName = ucfirst($tx["store"]);
                    $getIcon = $this->cstorePaymentIcon(strtolower($storeName));
                    $paymentCode = $tx["payment_code"];
                    $getPrice = $tx["gross_amount"];
                    $getTxTime = $tx["transaction_time"];
                    $transactionTime = date('d/m/Y', strtotime($getTxTime));
                    $paymentDueDate = date('d M Y, H:i', strtotime("+1 day", strtotime($getTxTime)));
                    $totalPayment = "Rp" . number_format($getPrice,0,"",".");
                    $this->updateOrderStatusPending($orderId);
        
                    ?><div class="content-card-wp">
                        <div class="card-child-left-wp">
                            <div class="left-child-sec1-wp">
                                <div id="inv-id-wp"><?= $orderId ?></div>
                                <div id="date-wp"><?= $transactionTime ?></div>
                            </div>
                            <div class="left-child-sec2-wp">
                                <div id="child-img"><?= $getIcon ?></div>
                                <div class="text-sec-wp">
                                    <div class="text-pd-wp">
                                        <p>Metode Pembayaran</p>
                                        <h3><?= $storeName ?></h3>
                                    </div>
                                    <div class="text-pd-wp">
                                        <p>Kode Pembayaran</p>
                                        <h3><?= $paymentCode ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="left-child-sec3-wp">
                                <button>Detail Pembelian</button>
                            </div>
                        </div>
                        <div class="card-child-right-wp">
                            <div class="right-child-sec1-wp">
                                <p>Bayar sebelum</p>
                                <span><?= $paymentDueDate ?></span>
                            </div>
                            <div class="right-child-sec2-wp">
                                <div class="grand-total-wp">
                                    <p>Total Pembelian</p>
                                    <h3><?= $totalPayment ?></h3>
                                </div>
                            </div>
                        </div>
                    </div><?php
                }elseif ($paymentType == "qris") { //QRIS, etc
                
                    $orderId = $tx["order_id"];
                    $txIdQRIS = $tx["transaction_id"];
                    $storeName = strtoupper($tx["payment_type"]);
                    $getIcon = $this->cstorePaymentIcon(strtolower($storeName));
                    $getPrice = $tx["gross_amount"];
                    $getTxTime = $tx["transaction_time"];
                    $transactionTime = date('d/m/Y', strtotime($getTxTime));
                    $expiredTime = strtotime($tx["expire_time"]);
                    $paymentDueDate = date("d M Y H:i", $expiredTime);
                    $totalPayment = "Rp" . number_format($getPrice,0,"",".");
                    $this->updateOrderStatusPending($orderId);
                    
                    ?><div class="content-card-wp">
                        <div class="card-child-left-wp">
                            <div class="left-child-sec1-wp">
                                <div id="inv-id-wp"><?= $orderId ?></div>
                                <div id="date-wp"><?= $transactionTime ?></div>
                            </div>
                            <div class="left-child-sec2-wp">
                                <div id="child-img"><img src="https://ik.imagekit.io/samiha/payment_logo/QRIS__Quick_Response_Code_Indonesia_Standard__Logo__PNG720p__-_Vector69Com_xEer30mb8g.png"></div>
                                <div class="text-sec-wp">
                                    <div class="text-pd-wp">
                                        <p>Metode Pembayaran</p>
                                        <h3><?= $storeName ?></h3>
                                    </div>
                                    <div class="text-pd-wp">
                                        <p>Kode Pembayaran</p>
                                        <h3><a href="https://api.veritrans.co.id/v2/qris/<?= $txIdQRIS ?>/qr-code" target="_blank">Lihat QR</a></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="left-child-sec3-wp">
                                <button>Detail Pembelian</button>
                            </div>
                        </div>
                        <div class="card-child-right-wp">
                            <div class="right-child-sec1-wp">
                                <p>Bayar sebelum</p>
                                <span><?= $paymentDueDate ?></span>
                            </div>
                            <div class="right-child-sec2-wp">
                                <div class="grand-total-wp">
                                    <p>Total Pembelian</p>
                                    <h3><?= $totalPayment ?></h3>
                                </div>
                            </div>
                        </div>
                    </div><?php
                }
            }elseif($statusInfo == "404"){
                    echo "Belum ada transaksi";
            }
        }
        if ($status != "pending") { $status = "Belum ada transaksi"; };
    }

    private function allFilter(){
        $db = $this->getDb();
        $userId = $this->getUserId();
        $invoiceDataQuery = mysqli_query($db, "SELECT * FROM invoice WHERE userId = '$userId' ORDER BY invoiceIdPK DESC");
        $invoiceDataCheck = mysqli_num_rows($invoiceDataQuery);
        if ($invoiceDataCheck <= 0) {
            echo "belum ada transaksi";
        }else{
            foreach ($invoiceDataQuery as $data) {
                error_reporting(0);
                $invoiceId = $data["invoiceId"];
                $getDataAPI = $this->getDataAPI($invoiceId);
                $transactionStatus = $getDataAPI["transaction_status"];
                $orderId = $getDataAPI["order_id"];
                $this->showAllFilter($transactionStatus, $orderId);
            }
        }
        
    }

    private function showAllFilter($txStatusAPI, $invoiceIdAPI){
        if($txStatusAPI == "settlement") {
            $this->getInvoiceDataQuery($invoiceIdAPI);
        }
    }
    
    private function successFilter(){
        // echo "transaksi berhasil";
    }
    
    private function notsucessFilter(){
        // echo "transaksi tidak berhasil";
    }

    public function catchFilter($filter){
        return $this->getFilter($filter);
    }

    public function noFilterHome(){
        return $this->allFilter();
    }
}

class transactionManagementUser{

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

    private function showDetail($data){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $db = $this->getDb();
        $invoiceId = $this->sanitize($data);
        $paymentData = $this->allPaymentData($invoiceId);

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
        $allProduct = $allData["invoice_item"];
        $allTotalPayment = $allData["payment_data"]["total_payment"];
        $getShippingName = htmlspecialchars($shippingType, ENT_QUOTES);
        $shippingNameFinal = strtolower($getShippingName);

        ?>
        <div class="main-ct-order">
            <div class="top-order-sec">
                <div class="row-order">
                    <span><b>InvoiceId: </b></span>
                    <span><?= $invoiceId ?></span>
                </div>
                <div class="row-order">
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
                        <div id="resi-title">Resi Pengiriman: <?= $detailResi ?></div>
                        <div id="generate-resi-link">
                            <?php $this->resiCheck($shippingNameFinal); ?>
                        </div>
                    </div>
                </div>
                <div class="total-price-sec"><span><b>Total Pembayaran: </b></span>Rp<?= number_format($allTotalPayment,0,"","."); ?></div>
                <div id="goBackList"><a href="./">Kembali</a></div>
            </div>
        </div>
        <script src="../js/jquery-3.6.0.min.js"></script>
        <?php
    }

    private function resiCheck($param){
        if (str_contains($param, "jne")) {
            echo "<a href='https://www.jne.co.id/id/beranda'>Cek Status Pengiriman</a>";
        }elseif (str_contains($param, "ez")) {
            echo "<a href='https://jet.co.id/track'>Cek Status Pengiriman</a>";
        }elseif (str_contains($param, "pos")) {
            echo "<a href='https://www.posindonesia.co.id/id/tracking'>Cek Status Pengiriman</a>";
        }elseif (str_contains($param, "anteraja")) {
            echo "<a href='https://anteraja.id/id/'>Cek Status Pengiriman</a>";
        }
    }

    //setter getter
    public function showOrderDetail($data){
        return $this->showDetail($data);
    }
}