/* beautify ignore:start */
@@include('./_calculator.js');
/* beautify ignore:end */

(function ($) {
    $(document).ready(function () {

        if ($('.iute-calculator').length) {
            var calculator;
            setTimeout(function () {

                calculator = new EshopCalculator();

                calculator.initWithLiveAgentInformation(
                    iute_params.country,
                    iute_params.currency,
                    iute_params.api_url,
                    iute_params.agent_id,
                    []
                );

            }, 1000);

            $(document).on("ajaxComplete", function( event, xhr, settings){
                if(settings.url === '/?wc-ajax=update_order_review'){
                    if(typeof calculator === 'object'){
                        calculator.initWithLiveAgentInformation(
                            iute_params.country,
                            iute_params.currency,
                            iute_params.api_url,
                            iute_params.agent_id,
                            []
                        );
                    }
                }
            });
        }

        if ($('.single-product').length) {

            var calculator = new EshopCalculator();

            var params = calculator.fetchInputParameters(
                iute_params.country,
                iute_params.currency,
                iute_params.api_url,
                iute_params.agent_id
            );

            var calcParams = calculator.parseProductSettingsAndAddToCalculationSets(
                params.loanProductParametersResponseList[0], 0
            );

            calculator.initWithLiveAgentInformation(
                iute_params.country,
                iute_params.currency,
                iute_params.api_url,
                iute_params.agent_id,
                []
            );

            function monthlyPayment(month) {
                if(iute_params.use_api == 'yes'){
                    result = '';
                    var loanAmount = parseFloat($('#amount').val());
                    $.ajax({
                        type: "GET",
                        async: false,
                        contentType: "application/json",
                        url: iute_params.api_url + "/rest/public/e-shop/minMonthlyRepayment/1.0/country/" + iute_params.country + "/agent/" + iute_params.agent_id + "/amount/" + loanAmount,
                        success: function (data) {
                            if (!data) {
                                return;
                            }

                            // OM
                            result = data['monthlyRepayments'][month-1]['monthlyRepayment'];
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    })
                    return result;
                }
                else{
                    var calcPeriod = calcParams.amountLimitedPeriodParameters[0].periodParameters[month];
                    var monthlyPayment = calculator.calculateForPeriod(calcPeriod);
                    return monthlyPayment.monthlyPayment;
                }
            }

            $('.iute-loan__price label').text(monthlyPayment(12));

            $('.iute-calc-single-range').change(function () {

                $('.iute-calc-single time span').text($(this).val());
                $('.iute-loan__price label').text(monthlyPayment($(this).val()));

            });

        }


    });
})(jQuery);