<?php

$getHome = new homeManagement;
$dbElement = $getHome->getElement("samiha-logo");
foreach ($dbElement as $dashboard) {
    ?><footer class="footer">
        <div class="footer-left">
            <img src="<?= $dashboard["url"] ?>" alt="">
        </div>
        <ul class="footer-right">
            <li>
                <h2>Samiha</h2>

                <ul class="footer-box">
                    <li><a href="/article/view?title=tentang-kami-samiha-kurma">Tentang Kami</a></li>
                    <li><a href="whatsapp://send?text=Halo! Saya ingin bertanya terkait produk kurma dari Samiha&phone=+628111960822">Kontak</a></li>
                    <li><a href="../article/">Artikel</a></li>
                </ul>
            </li>
            <li class="bantuan">
                <h2>Bantuan dan Panduan</h2>

                <ul class="footer-box">
                    <li><a href="#">Syarat dan Ketentuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Frequently Asked Questions (FAQ)</a></li>
                </ul>
            </li>
            <div class="medsos">
                <h2>IKUTI KAMI</h2>
                <a href="#"><img src="https://cdn.icon-icons.com/icons2/1826/PNG/512/4202107facebookfblogosocialsocialmedia-115710_115591.png" alt="" width="22px"></a>
                <a href="https://www.instagram.com/samihapremiumqurma/"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/2048px-Instagram_logo_2016.svg.png" alt="" width="22px"></a>
            </div>
        </ul>
        <div class="footer-bottom">
            <p>© 2022 | Samiha</p>
        </div>
    </footer>
<?php }