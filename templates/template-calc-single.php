<?php
$life_time = 1 * 60 * 60 * 24 * 7;
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $life_time) . ' GMT');
?>
<div class="iute-calc-single">
    <input id="amount" class="range-slider__value" name="loan_amount" type="hidden" value="<?php echo $cost; ?>" min="0" max="10000" />
    <time>
        <span>12</span>
        <?php _e('Muaj', PNAME) ?>
    </time>
    <input class="iute-calc-single-range" type="range" min="2" max="24" name="" value="12" placeholder="">
    <div class="iute-loan">
        <span><?php _e('Kësti Mujorsss', PNAME) ?></span>
        <span class="iute-loan__price">
            <label style="color:white"></label>
            <?php echo get_woocommerce_currency_symbol(); ?>
        </span>
    </div>
</div>