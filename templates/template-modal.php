<div class="modal fade" id="iute-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body text-center">
                <div class="mb-3">
                    <img class="img-fluid" style="width: 210px;" src="https://www.staging.bss.com.al/wp-content/uploads/2021/07/Logo-BSS-removebg-preview.png" alt="">
                </div>
                <h3>Bli me këste</h3>
                <div class="form-response"></div>
                <form action="" method="POST" class="form" id="loan-form">
                    <div class="form-response-error"></div>
                    <input type="hidden" value="<?php the_title(); ?>" name="product-name">
                    <input type="hidden" value="<?php the_permalink(); ?>" name="product-link">
                    <input type="hidden" class="disabled" value="<?php echo $priceZ ?>" id="vleraForm" name="amount">
                    <input type="hidden" class="disabled" value="" id="monthForm" name="muajt">
                    <input type="hidden" class="disabled" value="" id="skuForm" name="sku">
                    <div class="custom-slider-holder"></div>
                    <!-- WILL BE DELETED -->
                    <div class="custom-slider-holder">
                        <div class="xxiute-calc-single">
                            <input id="xxamount" class="xxrange-slider__value" name="loan_amount" type="hidden" value="<?php echo $priceZ ?>" min="0" max="200000">
                            <time>
                                <span>12</span>
                                Muaj
                            </time>                       
                            <div class="mb-1">
								<input class="form-range xxiute-calc-single-range" type="range" min="2" max="36" name="range-slider-months" value="12" placeholder="">
							</div>
							<span class="btn btn-outline-primary btn-lg">
								<div class="xxiute-loan">
                                <span>Kësti Mujor</span>
                                <span class="xxiute-loan__price"><label>0</label> Lekë</span>
                            </div>
							</span>
                
                        </div>
                    </div>
                    <!-- WILL BE DELETED -->
                    <div class="row mb-2 mt-3">
                        <div class="col-6">
                            <input placeholder="Emri" class="form-control" type="text" value="" id="emer" name="emer" required>
                        </div>
                        <div class="col-6">
                            <input placeholder="Mbiemër" class="form-control" type="text" value="" id="mbiemer" name="mbiemer" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <input placeholder="Datelindja(psh. 24.04.1987)" class="form-control" type="text" value="" id="birth" name="birth" required>
                        </div>
                        <div class="col-6">
                            <input placeholder="Nr.Telefonit(psh.0629999999)" class="form-control" type="phone" value="" id="phone" name="phone" required minlength="10">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <input placeholder="Numri i Kartes ID(psh.J12345678X)" class="form-control" type="text" value="" id="idCard" name="idCard" required maxlength="10">
                        </div>
                        <div class="col-6">
                            <input placeholder="E-Mail" class="form-control" type="email" value="" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input placeholder="Adresa" class="form-control" type="text" value="" id="address" name="address" required minlength="3">
                        </div>
                        <div class="col-6">
                            <input placeholder="Qyteti" class="form-control" type="text" value="" id="city" name="city" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <div class="form-check">
                            <input type="hidden" status="">
                            <input class="form-check-input" type="checkbox" required name="terms">
                            <span class="checkmark"></span>
                            <label class="form-check-label">
                                Pajtohem me <a href="#">termat dhe kushtet</a> për proçesimin e të dhënave personale dhe jap pëlqimin tim per të kërkuar në rregjistrin e kredive të Bankës së Shqipërisë
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 text-end">
                        <input class="btn btn-danger" type="submit" value="Apliko">
                    </div>
                    <div class="mb-3">
                        <img class="img-fluid" src="https://assets.mintos.com/068F60BF-D931-82CD-53C2-B375F3CF346E.png" style="width: 180px; margin-top: -85px;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (!empty($_POST['amount']) && !empty($_POST['muajt']) && !empty($_POST['emer']) && !empty($_POST['mbiemer']) && !empty($_POST['idCard']) && !empty($_POST['email']) && !empty($_POST['phone'])  && !empty($_POST['birth'])) {

    $url = 'https://api.iutecredit.com/rest/e-shop/application/1.0';
    $username = 'username'; // iute username
    $password = 'password'; // iute password
    $phone = $_POST["phone"];
    $months = $_POST["muajt"];
    $amount = $_POST["amount"];

    $productId = '133';

    // if ((int)$amount <= (int)"30000") {
    //     $productId = '241';
    // } elseif ((int)$amount <= (int)"60000") {
    //     $productId = '133';
    // } elseif ((int)$amount > (int)"60000") {
    //     $productId = '240';
    // }

    $idCardnumber = $_POST['idCard'];
    $fields = [
        'agentId' => urlencode('1352'),
        'productId' => urlencode($productId),
        'amount' => urlencode($_POST["amount"]),
        'period' => urlencode($_POST["muajt"]),
        'firstName' => urlencode($_POST['emer']),
        'lastName' => urlencode($_POST['mbiemer']),
        'idnp' => urlencode(strtoupper($idCardnumber)),
        'email' => $_POST['email'],
        'code' => urlencode(time()),
        'phoneNr' => str_replace('+355', '0', $phone),
        'birthDate' => urlencode($_POST['birth']),
        'country' => urlencode('AL'),
        'consent' => urlencode('true'),
        'loanSigning' => urlencode('dealer'),
        'bankAccountNr' => urlencode('asd')
    ];

    $fields_string = "";
    // url-ify the data for the POST
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    //execute post
    $result = curl_exec($ch);
    if (strpos($result, 'SUCCESS') !== false) {
?>
        <div class="resultz success">Kërkesa juaj u dërgua me sukses</div>
        <script>
            var iuteModal = new bootstrap.Modal(document.getElementById('iute-modal'));
            iuteModal.show();
            $('.form-response').html('<div class="resultz success">Kërkesa juaj u dërgua me sukses</div>');
            $('#iute-modal .form').hide();
        </script>
    <?php
        create_custom_order($_POST["sku"]);
    } else {
    ?>
        <div class="resultz error">Ka patur një problem, ju lutemi kontronlloni të gjitha fushat.</div>
        <script>
            var iuteModal = new bootstrap.Modal(document.getElementById('iute-modal'));
            iuteModal.show();
            $('.form-response-error').html('<div class="resultz error">Ka patur një problem, ju lutemi riplotësoni të gjitha fushat saktë.</div>');
        </script>
<?php
    }
    //close connection
    curl_close($ch);
}
?>