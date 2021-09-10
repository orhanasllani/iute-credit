<?php
wp_die("ORHANASLLANI");
$life_time = 1 * 60 * 60 * 24 * 7;
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $life_time) . ' GMT');
?>
<div class="iute-calculator">

    <input type="hidden" name="iute_appl_pr_id" value="">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist"></div>
    </nav>
    <div></div>
    <div>
        <div class="col">
            <div class="--range">
                <div class="col-sm-10">
                    <input id="rangeInput" class="range-slider__range-range" type="range" step="500" min="0" max="10000" />
                </div>
                <div class="col-sm-2">
                    <input id="amount" class="range-slider__value" name="loan_amount" type="number" value="<?php echo $amount; ?>" min="0" max="10000" />
                </div>
            </div>
            <h4 class="text-left"><?php echo __('Periudha', PNAME); ?></h4>
            <div>
                <div class="col-sm-10">
                    <input id="periodInput" class="range-slider__range-range" name="loan_period" type="range" min="1" max="5" />
                </div>
                <div class="col-sm-2">
                    <input id="months" class="range-slider__value" type="number" value="1" min="1" max="5" />
                </div>
            </div>
            <div></div>
            <div>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td width="50%"><strong><?php echo __('Shuma për aplikim', PNAME); ?></strong></td>
                            <td width="50%"><span id="loanAmount" class="loan-amount">--</span></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Periudha', PNAME); ?></strong></td>
                            <td><span id="loanPeriod" class="loan-period">--</span></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo __('Pagesa mujore', PNAME); ?></strong></td>
                            <td><span id="monthlyPayment" class="monthly-repayment">--</span></td>
                        </tr>

                        <?php if ($options->get_option('info_commision') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('Komisioni', PNAME); ?></strong></td>
                                <td><span id="commissionFee" class="comm-fee">--</span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($options->get_option('info_admin_fee') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('Komision Administrimi', PNAME); ?></strong></td>
                                <td><span id="adminFee" class="admin-fee">--</span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($options->get_option('info_interest_cost') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('Interesi', PNAME); ?></strong></td>
                                <td><span id="interestCost" class="interest-cost">--</span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($options->get_option('info_total') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('Totali', PNAME); ?></strong></td>
                                <td><span id="totalCost" class="total-cost">--</span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($options->get_option('info_apr') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('APR', PNAME); ?> %</strong></td>
                                <td><span id="apr" class="apr">--</span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if ($options->get_option('info_xirr') == 'yes') : ?>
                            <tr>
                                <td><strong><?php echo __('XIRR', PNAME); ?> %</strong></td>
                                <td><span id="xirr" class="xirr">--</span></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="iute-application">
    <h4><?php _e('Të dhënat tuaja', PNAME); ?></h4>

    <input type="text" name="iute_appl_fname" value="<?php echo $data['first_name']; ?>" placeholder="<?php _e('Emri', PNAME); ?>" required>

    <?php if ($options->get_option('info_patr') == 'yes') : ?>
        <input type="text" name="iute_appl_patronymic" placeholder="<?php _e('Atësia', PNAME); ?>">
    <?php endif; ?>

    <input type="text" name="iute_appl_lname" value="<?php echo $data['last_name']; ?>" placeholder="<?php _e('Mbiemri', PNAME); ?>" required>

    <input type="text" name="iute_appl_bdate" placeholder="<?php _e('Datëlindja (psh. 30.12.1990)', PNAME); ?>" pattern="^([0-2][0-9]|(3)[0-1])(\.)(((0)[0-9])|((1)[0-2]))(\.)\d{4}$" class="datepicker" required="" />

    <input type="text" name="iute_appl_idnp" placeholder="<?php _e('NR. Personal/ID', PNAME); ?>" required>

    <input type="email" name="iute_appl_email" value="<?php echo $data['email']; ?>" placeholder="<?php _e('Email', PNAME); ?>" required>

    <input type="text" name="iute_appl_phone" value="<?php echo $data['phone']; ?>" placeholder="<?php _e('Nr. Telefoni', PNAME); ?>" required>




    <?php if ($options->get_option('info_bank') == 'yes') : ?>
        <input type="text" name="iute_appl_bank_nr" placeholder="<?php _e('Bank account number', PNAME); ?>">
    <?php endif; ?>

    <input type="hidden" name="iute_appl_sign" value="dealer">
    <a class="required"><?php _e('Ju lutemi kontaktoni me dyqanin për të verifikuar nëse produkti është në gjëndje!', PNAME); ?></a>

    <label for="iute_appl_agree"><?php echo $options->get_option('user_agree'); ?></label>
    <input id="iute_appl_agree" type="checkbox" name="iute_appl_agree" required>
    <a for="iute_appl_agree"><?php echo $options->get_option('user_agree'); ?>I kam lexuar dhe i pranoj termat dhe kushtet e perdorimit</a>
</div>