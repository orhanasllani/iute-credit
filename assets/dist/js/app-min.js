var loanA, LoanM, EshopCalculator = function () { var e = this, t = { general: { "invalid.period.or.loan.amount": ["Invalid amount or period for calculations"] }, 400: { "no.active.agent": ["No active agent"] }, 404: ["not.found", "Invalid URL"], 500: ["internal.error", "Internal error"], 503: ["service.unavailable", "Service unavailable"] }, a = { PERIOD_INFORMATION_TEMPLATE: "([monthly-payment] [currency])" }; this.daysBetween = function (e, t) { return Math.round(Math.abs((e.getTime() - t.getTime()) / 864e5)) }, this.fetchInputParameters = function (e, t, a, n) { this.country = e, this.agentId = n, this.url = a; var i = this, r = void 0; return $.ajax({ type: "GET", async: !1, contentType: "application/json", url: this.url + "/rest/public/e-shop/parameters/multi/1.0/country/" + this.country + "/agent/" + this.agentId, success: function (e) { e && (i.debug("Fetched data:"), i.debug(e), r = e) }, error: function (e) { i.handleErrors(e) } }), r }, this.handleErrors = function (e) { var a = t[e.status], n = null; if (a) if (400 === e.status) { var i = a[JSON.parse(e.responseText).property]; n = i[i.length - 1] } else n = a[a.length - 1]; else n = e.responseText; n && this.showError(n) }, this.getErrorTranslation = function (e, a) { var n = t[e][a]; return n[n.length - 1] }, this.showErrorForLabel = function (e) { $("#error").html(this.getErrorTranslation("general", e)), $("#errorDiv").show() }, this.showError = function (e) { $("#error").html(e), $("#errorDiv").show() }, this.hideError = function () { $("#error").html(""), $("#errorDiv").hide() }, this.useDebugLog = function () { this.debugEnabled = !0 }, this.debug = function (e) { this.debugEnabled && console.log(e) }, this.initWithLiveAgentInformation = function (e, t, n, i, r) { this.country = e, this.currency = t, this.url = n, this.agentId = i, this.calculatorOptions = {}, this.disabledCustomProducts = r.length > 0 ? r : [], this.getCalculatorOptions(), this.templates = a }, this.initWithManualSettings = function (e, t, n, i) { this.country = e, this.currency = t, this.calculatorOptions = i, this.initShopFeeDefaultsIfNeeded(n), this.initClientCommissionDefaults(n), this.calculatorParameters = {}; var r = null; if (Array.isArray(n)) r = this.handleManualInputSettingsArray(n); else { var o = this.parseProductSettingsAndAddToCalculationSets(n, 0); this.addProductTabs(o, 0, !0), this.calculatorParameters[o.customProductId] = o, r = o } this.templates = a, this.setSelectedProduct(r.customProductId), this.updateView(r), this.calculate(r) }, this.handleManualInputSettingsArray = function (t) { var a = null; if (this.calculatorOptions.combineProducts) a = this.combineInputProducts(t); else { for (var n in t) { var i = e.parseProductSettingsAndAddToCalculationSets(t[n], 0); this.addProductTabs(i, n, !0), this.calculatorParameters[i.customProductId] = i } a = this.calculatorParameters[t[0].customProductId] } return a }, this.combineInputProducts = function (t) { var a = t[0], n = e.parseProductSettingsAndAddToCalculationSets(a, 0); for (var i in n.customProductId = -1, n.customProductName = "Combined product", t) if (0 != i) { var r = t[i]; if (r.maxPeriod > a.maxPeriod) { n.maxPeriod = r.maxPeriod, r.maxAmount > a.maxAmount && (n.maxAmount = r.maxAmount); var o = this.parsePeriodSettings(r, 0); n.amountLimitedPeriodParameters[i] = o } } return this.calculatorParameters[n.customProductId] = n, this.addProductTabs(n, 0, !1), n }, this.initShopFeeDefaultsIfNeeded = function (e) { if (Array.isArray(e)) for (var t = 0; t < e.length; ++t)this.initShopFeeArrayWithDefaultValues(e[t]); else this.initShopFeeArrayWithDefaultValues(e) }, this.initShopFeeArrayWithDefaultValues = function (e) { if (!e.parameters.SHOP_FEE) { for (var t = [], a = e.minPeriod; a <= e.maxPeriod; ++a)t.push(0); e.parameters.SHOP_FEE = t } }, this.initClientCommissionDefaults = function (e) { if (Array.isArray(e)) for (var t = 0; t < e.length; ++t)this.initClientCommissionArrayWithDefaultValues(e[t]); else this.initClientCommissionArrayWithDefaultValues(e) }, this.initClientCommissionArrayWithDefaultValues = function (e) { if (!e.clientCommission) { for (var t = [], a = e.minPeriod; a <= e.maxPeriod; ++a)t.push(!1); e.clientCommission = new Object, e.clientCommission.COMMISSION_FOR_CLIENT = t } }, this.changeTemplate = function (e, t) { this.templates[e] = t }, this.processTemplate = function (e, t) { var a = this.templates[e]; return a = (a = (a = (a = (a = (a = (a = a.replace("[monthly-payment]", t.monthlyPayment)).replace("[apr]", t.apr)).replace("[commission-fee]", t.commissionFee)).replace("[admin-fee]", t.adminFee)).replace("[interest-cost]", t.interestCost)).replace("[total-cost]", t.totalCost)).replace("[currency]", this.currency) }, this.setSelectedProduct = function (e) { this.selectedProduct = e }, this.addTranslations = function (a) { Object.keys(a).forEach(function (n) { Object.keys(t).forEach(function (t) { e.addTranslation(t, n, a) }) }) }, this.addTranslation = function (e, a, n) { var i = t[e]; 400 == e || "general" == e ? Object.keys(i).forEach(function (i) { i === a && t[e][i].push(n[a]) }) : i[0] === a && t[e].push(n[a]) }, this.addCommas = function (e) { for (var t = (e += "").split("."), a = t[0], n = t.length > 1 ? "." + t[1] : "", i = /(\d+)(\d{3})/; i.test(a);)a = a.replace(i, "$1 $2"); return a + n }, this.getCalculatorOptions = function () { var e = this; $.ajax({ type: "GET", contentType: "application/json", url: this.url + "/rest/public/e-shop/parameters/multi/1.0/country/" + this.country + "/agent/" + this.agentId, success: function (t) { if (t) { for (var a in e.debug("Fetched data:"), e.debug(t), "undefined" != typeof iute_compaign && t.loanProductParametersResponseList.map(function (e, a) { e.customProductId == iute_compaign.hide && t.loanProductParametersResponseList.splice(a, 1) }), e.calculatorParameters = new Object, e.calculatorOptions = void 0 !== t.calculatorOptions ? t.calculatorOptions : {}, t.loanProductParametersResponseList) { var n = t.loanProductParametersResponseList[a].customProductId; if (-1 == e.disabledCustomProducts.indexOf(n)) { var i = e.parseProductSettingsAndAddToCalculationSets(t.loanProductParametersResponseList[a], 0); e.addProductTabs(i, a, !0), e.calculatorParameters[i.customProductId] = i } } var r = t.loanProductParametersResponseList[0].customProductId, o = e.calculatorParameters[r]; e.setSelectedProduct(r), e.updateView(o), e.calculate(o) } }, error: function (t) { e.handleErrors(t) } }) }, this.parseProductSettingsAndAddToCalculationSets = function (e, t) { var a = { customProductId: e.customProductId, customProductName: e.customProductName, calculateInterestOnlyOnPrincipal: void 0 !== e.calculateInterestOnlyOnPrincipal && e.calculateInterestOnlyOnPrincipal, customerDisclaimer: e.customerDisclaimer, minAmount: e.minAmount, maxAmount: e.maxAmount, minPeriod: e.minPeriod, maxPeriod: e.maxPeriod, amountLimitedPeriodParameters: {} }; return a.amountLimitedPeriodParameters[t] = this.parsePeriodSettings(e, 0), a }, this.parsePeriodSettings = function (e, t) { for (var a = t, n = { minAmount: e.minAmount, maxAmount: e.maxAmount, minPeriod: e.minPeriod, maxPeriod: e.maxPeriod, periodParameters: {} }, i = e.minPeriod; i <= e.maxPeriod; i++) { var r = { period: i, forReturningCustomerOnly: void 0 !== e.forReturningCustomerOnly && e.forReturningCustomerOnly, customerDisclaimer: e.customerDisclaimer, calculateOnlyForPrincipal: e.calculateInterestOnlyOnPrincipal, interest: e.parameters.INTEREST[a], comm_fee: e.parameters.COMMISSION[a], shop_fee: e.parameters.SHOP_FEE[a], admin_fee: e.parameters.ADMIN_FEE[a], client_commission: e.clientCommission.COMMISSION_FOR_CLIENT[a] }; n.periodParameters[i] = r, a++ } return n }, this.fetchCombinedPeriodsForCalculatorParameters = function (e) { var t = []; for (var a in e.amountLimitedPeriodParameters) { var n = e.amountLimitedPeriodParameters[a]; for (var i in n.periodParameters) { var r = n.periodParameters[i]; -1 == t.indexOf(r.period) && t.push(r.period) } } return t }, this.findAmountLimitedPeriodParameterSets = function (e) { var t = [], a = this.getCurrentAmountSelectionValue(), n = this.getCurrentPeriodSelectionValue(); for (var i in e) { var r = e[i]; a >= r.minAmount && a <= r.maxAmount && n >= r.minPeriod && n <= r.maxPeriod && (this.debug("Found period parameter set for amount: " + a), t.push(r)) } return t }, this.findPeriodParameterSet = function (e, t) { for (var a in e) if (void 0 !== e[a].periodParameters[t]) return e[a].periodParameters[t]; return null }, this.getCombinedParameterSets = function (e) { var t = {}; for (var a in e) for (var n in e[a].periodParameters) { var i = e[a].periodParameters[n]; t[n] || (t[n] = i) } return t }, this.getVisibleTabPeriods = function (e) { var t = []; for (var a in e) { var n = e.length, i = Math.ceil(n / 10); n > 10 && a % i > 0 || t.push(e[a]) } return t }, this.calculateLoanConditions = function (e) { this.debug("Calculating conditions..."); var t = this.calcExtras(e); this.debug("Total extra cost: " + t), e.extraFee += t; var a = this.monthlyPayment(e); this.debug("Monthly payment: " + a); var i = n(a, e), r = this.calcApr(e, a), o = this.calculateXIRR(e, i, a), s = { loanAmount: e.amount.toFixed(2), loanPeriod: e.numPayments, monthlyPayment: a.toFixed(2), commissionFee: e.clientCommissionAmount.toFixed(2), adminFee: e.adminFeeAmount.toFixed(2), interestCost: Math.abs(i - e.amount - e.adminFeeAmount - e.clientCommissionAmount).toFixed(2), totalCost: i.toFixed(2), apr: r, xirr: o.toFixed(2), forReturningCustomerOnly: e.forReturningCustomerOnly, customerDisclaimer: e.customerDisclaimer }; return this.debug("Finished calculating conditions"), s }; var n = function (e, t) { return 0 === t.interestRate ? t.amount + t.extraFee + t.adminFeeAmount : e.toFixed(2) * t.numPayments }; this.calculateForSelected = function () { return null == this.selectedProduct ? void console.log("No calculation parameters.") : this.calculate(this.calculatorParameters[this.selectedProduct]) }, this.calculateForProduct = function (e) { var t = this.calculatorParameters[e]; this.updateView(t), this.calculate(t) }, this.calculate = function (e) { this.debug("Calculating..."), $(".period-monthly-payment").text(""); var t = this.findAmountLimitedPeriodParameterSets(e.amountLimitedPeriodParameters), a = this.findPeriodParameterSet(t, this.getCurrentPeriodSelectionValue()); if (a) { this.hideError(); var n = this.calculateForPeriod(a); this.debug("Calculated results for period: " + this.getCurrentPeriodSelectionValue()), this.debug("Calculating results for all other periods..."), this.calculateLoanConditionsForAllPeriods(e), this.debug("Finished calculations"), this.updateResultsView(n), this.debug("Finished updating view") } else this.showErrorForLabel("invalid.period.or.loan.amount") }, this.calculateLoanConditionsForAllPeriods = function (e) { var t = this.getCombinedParameterSets(e.amountLimitedPeriodParameters), a = this.fetchCombinedPeriodsForCalculatorParameters(e), n = this.getVisibleTabPeriods(a); for (var i in n) { var r = t[n[i]], o = this.calculateForPeriod(r); $("#period-monthly-payment-" + e.customProductId + "-" + r.period).text(this.processTemplate("PERIOD_INFORMATION_TEMPLATE", o)) } }, this.calculateForPeriod = function (e) { var t = this.getCurrentAmountSelectionValue(), a = e.comm_fee, n = e.shop_fee, i = e.admin_fee, r = e.interest, o = e.client_commission, s = a / 100, u = i / 100, l = t * (n / 100), c = o ? t * s : t * s - l, m = { amount: t, numPayments: e.period, interestRate: parseFloat(r), commissionRate: s, commissionAmount: t * s, clientCommissionAmount: c, commissionPaidByClient: o, adminRate: u, adminFeeAmount: t * u * e.period, shopFeeAmount: l, extraFee: 0, compoundFrequency: void 0 !== this.calculatorOptions.compoundFrequency ? this.calculatorOptions.compoundFrequency : 12, paymentFrequency: 12, calculateOnlyForPrincipal: e.calculateOnlyForPrincipal, forReturningCustomerOnly: e.forReturningCustomerOnly, customerDisclaimer: e.customerDisclaimer }; return this.debug("Calculating for values:"), this.debug(m), this.calculateLoanConditions(m) }, this.monthlyPayment = function (e) { this.debug("Calculating monthly payment..."); var t = Math.pow(1 + e.interestRate / 100 / e.compoundFrequency, e.compoundFrequency / e.paymentFrequency) - 1; this.debug("r=" + t); var a = 0; t > 0 ? (a = (e.amount + e.extraFee) * t * Math.pow(1 + t, e.numPayments) / (Math.pow(1 + t, e.numPayments) - 1), this.debug("Result for r > 0: " + a)) : (a = (e.amount + e.extraFee) / e.numPayments, this.debug("Result for r <= 0: " + a)); var n = e.adminFeeAmount / e.numPayments; if (this.debug("Admin fee monthly: " + n), e.calculateOnlyForPrincipal) { var i = e.clientCommissionAmount / e.numPayments; this.debug("Client commission monthly: " + i), a += i } a += n; var r = Math.round(1e6 * a) / 1e6; return this.debug("Calculated total monthly payment: " + r), this.debug("Finished calculating monthly payment"), r }, this.calcApr = function (e, t) { this.debug("Calculating APR..."), this.debug(e), this.debug("Monthly payment for APR: " + t); var a = Math.round(1e6 * t) / 1e6; this.debug("Rounded monthly payment for APR: " + a); var n = a / e.amount; if (this.debug("Initial diff: " + n), 0 == e.extraFee && 0 == e.adminFeeAmount && 0 == e.interestRate) return this.debug("No extra fees nor interest. Not calculating APR."), 0; var i = .05, r = 0, o = 1; do { this.debug("Iteration start: " + o); var s = i * Math.pow(1 + i, e.numPayments); this.debug("fx = " + s); var u = Math.pow(1 + i, e.numPayments) - 1; this.debug("dx = " + u), r = s / u - n, this.debug("z = " + r), i -= r, this.debug("a = " + i), this.debug("Iteration end: " + o), o++ } while (Math.abs(r) > 1e-9); var l = 100 * e.compoundFrequency * (Math.pow(i + 1, e.paymentFrequency / e.compoundFrequency) - 1); this.debug("APR percentile: " + l); var c = Math.round(100 * l) / 100; return this.debug("Calculated APR: " + c), this.debug("Finished APR calculations"), c }, this.calcExtras = function (e) { var t = 0; return e.calculateOnlyForPrincipal || (t += e.clientCommissionAmount), Math.round(100 * t) / 100 }, this.calculateNetPresentValue = function (e, t) { var a = 0, n = t[0].date; for (var i in t) { var r = t[i]; a += r.cashFlow / Math.pow(1 + e, this.daysBetween(n, r.date) / 365) } return a }, this.prepareTransactions = function (e, t, a) { this.debug("Preparing transactions for XIRR"); var n = e.amount; this.debug("Principal amount: " + n); var i = new Object, r = new Date; i[0] = { cashFlow: -Math.abs(n), date: r }, this.debug("Added initial transaction: "), this.debug(i[0]); var o = t; this.debug("Initial payback reminder: " + o); for (var s = 1; s <= e.numPayments; s++) { this.debug("Preparing transaction: " + s); var u = new Date(r); u.setMonth(u.getMonth() + s); var l = s == e.numPayments ? o.toFixed(2) : a; i[s] = { cashFlow: Math.abs(l), date: u }, this.debug(i[s]), o = Math.round(100 * (o - l)) / 100, this.debug("PaybackReminder: " + o) } return i }, this.calculateXIRR = function (e, t, a) { this.debug("Initial monthly payment fro XIRR: " + a); var n = Math.floor(100 * a) / 100; this.debug("Floored monthly payment for XIRR: " + n); var i = this.prepareTransactions(e, t, n), r = 0, o = .01, s = this.calculateNetPresentValue(r, i), u = this.calculateNetPresentValue(o, i); do { Math.abs(s) < Math.abs(u) ? s = this.calculateNetPresentValue(r += 1.6 * (r - o), i) : u = this.calculateNetPresentValue(o += 1.6 * (o - r), i) } while (s * u >= 0); var l = this.calculateNetPresentValue(r, i), c = l < 0 ? r : o, m = l < 0 ? o - r : r - o; do { var d = c + (m *= .5), p = this.calculateNetPresentValue(d, i); p <= 0 && (c = d) } while (Math.abs(p) >= 1e-6 && Math.abs(m) >= 1e-6); return Math.round(1e4 * d) / 100 }, this.getCurrentPeriodSelectionValue = function () { return parseInt($("#months").val()) }, this.getCurrentAmountSelectionValue = function () { return parseFloat($("#amount").val()) }, this.updateResultsView = function (e) { $("#loanAmount").html(this.addCommas(e.loanAmount) + " " + this.currency), $("#loanPeriod").html(e.loanPeriod), $("#newCustomer").prop("checked", !e.forReturningCustomerOnly), $("#returningCustomer").prop("checked", e.forReturningCustomerOnly), $("#customerDisclaimer").html(e.customerDisclaimer), $("#monthlyPayment").html(this.addCommas(e.monthlyPayment) + " " + this.currency), $("#commissionFee").html(this.addCommas(e.commissionFee) + " " + this.currency), $("#adminFee").html(this.addCommas(e.adminFee) + " " + this.currency), $("#interestCost").html(this.addCommas(e.interestCost) + " " + this.currency), $("#totalCost").html(this.addCommas(e.totalCost) + " " + this.currency), $("#apr").html(e.apr), $("#xirr").html(e.xirr) }, this.updateView = function (e) { $("#rangeInput").attr({ min: e.minAmount, max: e.maxAmount }), $("#amount").attr({ min: e.minAmount, max: e.maxAmount }); var t = $("#amount").val(); void 0 === t || 0 == t ? ($("#amount").val(e.minAmount), $("#rangeInput").val(e.minAmount)) : ($("#amount").val(t), $("#rangeInput").val(t)), $("#periodInput").attr({ min: e.minPeriod, max: e.maxPeriod }), $("#months").attr({ min: e.minPeriod, max: e.maxPeriod }); var a = $("#months").val(); void 0 === a || 0 == a || a < e.minPeriod ? ($("#months").val(e.minPeriod), $("#periodInput").val(e.minPeriod)) : ($("#months").val(a), $("#periodInput").val(a)) }, $("#amount").change(function () { $("#rangeInput").val($("#amount").val()), e.calculateForSelected() }), $("#rangeInput").on("input", function () { $("#amount").val($("#rangeInput").val()), e.calculateForSelected() }), $(document).delegate("#months", "change", function () { $("#periodInput").val($("#months").val()), e.calculateForSelected() }), $(document).delegate("#periodInput", "input", function () { $("#months").val($("#periodInput").val()), e.calculateForSelected() }), $(document).delegate("#nav-tab .nav-item", "click", function (t) { $(".nav-item").each(function () { $(this).removeClass("active"), $(this).attr("aria-selected", "false") }), $(".tab-pane").each(function () { $(this).removeClass("active"), $(this).removeClass("show") }), $(this).addClass("active"), $(this).attr("aria-selected", "true"); var a = Number(t.target.id.split("-")[2]); $("#product-" + a).addClass("active"), $("#product-" + a).addClass("show"), $('input[name="iute_appl_pr_id"]').val(a), e.setSelectedProduct(a), e.calculateForProduct(a) }), $(document).delegate("#nav-tabContent .list-group-item", "click", function () { var t = $(this).get(0).id; $("#months").val(t), $("#periodInput").val(t), e.calculateForSelected() }), this.addProductTabs = function (t, a, n) { var i = 0 == a ? "true" : "false", r = 0 == a ? "active" : ""; n && $("#nav-tab").append($('<a class="nav-item nav-link ' + r + '" id="product-tab-' + t.customProductId + '"data-toggle="tab" href="#product-' + t.customProductId + '" role="tab" aria-selected="' + i + '">' + t.customProductName + "</a>")), $("#nav-tabContent").append($('<div class="tab-pane fade show ' + r + '" id="product-' + t.customProductId + '" role="tabpanel" aria-labelledby="product-tab-' + t.customProductId + '">' + e.addTabContent(t) + "</div>")), $('input[name="iute_appl_pr_id"]').val().length || $('input[name="iute_appl_pr_id"]').val(t.customProductId) }, this.addTabContent = function (e) { var t = this.fetchCombinedPeriodsForCalculatorParameters(e), a = '<div id="product-period-content" class="list-group list-group-horizontal">', n = this.getVisibleTabPeriods(t); for (var i in n) a += '<div id="' + n[i] + '" class="list-group-item list-group-item-action">', a += '<div class="row justify-content-center"><h3>' + n[i] + "</h3></div>", a += '<div id="period-monthly-payment-' + e.customProductId + "-" + n[i] + '" class="row justify-content-center period-monthly-payment" style="font-size: 12px;"></div>', a += "</div>"; return a += "</div>", a += "Select period in months" } }; function iuteCalculateLoan() { currMonths = parseInt($(" .xxiute-calc-single time span").html()), currAmount = $(" #vleraForm").val(), console.log(currAmount, currMonths), calculateLoan(currAmount, currMonths) } function calculateLoan(e, t) { result = "", url = "https://api.iutecredit.com/rest/public/e-shop/minMonthlyRepayment/1.0/country/AL/agent/373/amount/" + e, $.getJSON(url, function (e) { console.log(e), file = e, result = file.monthlyRepayments[t - 1].monthlyRepayment, console.log(result), $(".xxiute-loan__price span").text(result) }) } !function (e) { e(document).ready(function () { e(".iute-calculator").length && (setTimeout(function () { (t = new EshopCalculator).initWithLiveAgentInformation(iute_params.country, iute_params.currency, iute_params.api_url, iute_params.agent_id, []) }, 1e3), e(document).on("ajaxComplete", function (e, a, n) { "/?wc-ajax=update_order_review" === n.url && "object" == typeof t && t.initWithLiveAgentInformation(iute_params.country, iute_params.currency, iute_params.api_url, iute_params.agent_id, []) })); if (e(".single.single-product").length) { var t, a = (t = new EshopCalculator).fetchInputParameters(iute_params.country, iute_params.currency, iute_params.api_url, iute_params.agent_id), n = t.parseProductSettingsAndAddToCalculationSets(a.loanProductParametersResponseList[0], 0); function i(a) { if ("yes" == iute_params.use_api) { result = ""; var i = parseFloat(e("#xxamount").val()); return e.ajax({ type: "GET", async: !1, contentType: "application/json", url: iute_params.api_url + "/rest/public/e-shop/minMonthlyRepayment/1.0/country/" + iute_params.country + "/agent/" + iute_params.agent_id + "/amount/" + i, success: function (e) { e && (result = e.monthlyRepayments[a - 1].monthlyRepayment) }, error: function (e) { console.log(e) } }), result } var r = n.amountLimitedPeriodParameters[0].periodParameters[a]; return t.calculateForPeriod(r).monthlyPayment } t.initWithLiveAgentInformation(iute_params.country, iute_params.currency, iute_params.api_url, iute_params.agent_id, []), e(".iute-loan__price label").text(i(12)), e(".iute-calc-single-range").change(function () { e(".iute-calc-single time span").text(e(this).val()), e(".iute-loan__price label").text(i(e(this).val())) }) } }) }(jQuery), $(".open-popup").on("click", function () { $(".single_add_to_cart_button").hasClass("disabled") && ($(".open-popup").addClass("disabled"), $(".one-click").addClass("disabled")), $(this).hasClass("disabled") ? alert("Ju lutemi, përzgjidhni ndonjë mundësi produkti, përpara se ta shtoni këtë produkt në shportën tuaj.") : ($(".loan-popup-container").addClass("open"), $("body").addClass("overflow"), months = $(".entry-summary > .iute-calc-single time span").html(), $("#monthForm").val(months)) }), $(".one-click").on("click", function () { $(".single_add_to_cart_button").hasClass("disabled") && ($(".open-popup").addClass("disabled"), $(".one-click").addClass("disabled")), $(this).hasClass("disabled") ? alert("Ju lutemi, përzgjidhni ndonjë mundësi produkti, përpara se ta shtoni këtë produkt në shportën tuaj.") : ($(".one-click-popup-container").addClass("open"), $("body").addClass("overflow")) }), $(".one-click-popup-container .close-button,.one-click-popup-container .overlay").on("click", function () { $(".one-click-popup-container").removeClass("open"), $("body").removeClass("overflow") }), $(".iute-calc-single-range, .custom-slider-holder .xxiute-calc-single-range").on("change", function () { months = $(".xxiute-calc-single-range").val(), console.log(months), $(".xxiute-calc-single time span").html(months), $("#monthForm").val(months), iuteCalculateLoan() }), $(".custom-slider-holder").on("mouseout", function () { months = $(".xxiute-calc-single time span").html(), $("#monthForm").val(months) }), $(".button-variable-item,.reset_variations").on("click", function () { setTimeout(function () { $(".button-variable-item").removeClass("not-available"), $(".variations option[disabled]").each(function () { val = $(this).val(), $('.button-variable-item[data-value="' + val + '"]').addClass("not-available") }), $(".variations_form .woocommerce-variation-price .price .amount").length ? newPrice = $(".variations_form .woocommerce-variation-price .price .amount").text() : newPrice = $("p.price span.woocommerce-Price-amount.amount").text(), secondPrice = newPrice.replace(",", ""), secondPriceNoCurr = parseInt(secondPrice), currentMonths = $("input.xxiute-calc-single-range").val(), "" != parseInt(secondPrice) && (console.log(parseInt(secondPrice)), $("#xxamount").val(parseInt(secondPrice)), $("#vleraForm").val(parseInt(secondPrice)), $(".custom-slider-holder .xxrange-slider__value").val(parseInt(secondPrice)), $("input.xxiute-calc-single-range").val(currentMonths)), $(".single_add_to_cart_button").hasClass("disabled") ? ($(".open-popup").addClass("disabled"), $(".one-click").addClass("disabled")) : ($(".open-popup").removeClass("disabled"), $(".one-click").removeClass("disabled")), secondPriceNoCurr > 2e5 ? $(".open-popup").hide() : $(".open-popup").show(), sku = $(".sku").html(), $("#skuForm").val(sku), $("#skuForm2").val(sku), iuteCalculateLoan() }, 300) }), $(".single-product .single-product-holder .summary form.cart.variations_form .variations tbody tr select").on("change", function () { setTimeout(function () { $(".variations_form .woocommerce-variation-price .price .amount").length ? newPrice = $(".variations_form .woocommerce-variation-price .price .amount").text() : newPrice = $("p.price span.woocommerce-Price-amount.amount").text(), secondPrice = newPrice.replace(",", ""), secondPriceNoCurr = parseInt(secondPrice), currentMonths = $("input.xxiute-calc-single-range").val(), "" != parseInt(secondPrice) && (console.log(parseInt(secondPrice)), $("#xxamount").val(parseInt(secondPrice)), $("#vleraForm").val(parseInt(secondPrice)), $(".custom-slider-holder .xxrange-slider__value").val(parseInt(secondPrice)), $("input.xxiute-calc-single-range").val(currentMonths)), $(".single_add_to_cart_button").hasClass("disabled") ? ($(".open-popup").addClass("disabled"), $(".one-click").addClass("disabled")) : ($(".open-popup").removeClass("disabled"), $(".one-click").removeClass("disabled")), secondPriceNoCurr > 2e5 ? $(".open-popup").hide() : $(".open-popup").show(), sku = $(".sku").html(), $("#skuForm").val(sku), $("#skuForm2").val(sku), iuteCalculateLoan() }, 300) }), $(window).on("load", function () { setTimeout(function () { $(".single_add_to_cart_button").hasClass("disabled") ? ($(".open-popup").addClass("disabled"), $(".one-click").addClass("disabled")) : ($(".open-popup").removeClass("disabled"), $(".one-click").removeClass("disabled")), $(".variations").length && $(".variations option[disabled]").each(function () { val = $(this).val(), $('.button-variable-item[data-value="' + val + '"]').addClass("not-available") }) }, 1e3), $(".payment_box.payment_method_iute-credit").length && $('<div class="bars"><span></span><span></span><span>3</span><span></span><span></span><span>6</span><span></span><span></span><span>9</span><span></span><span></span><span>12</span><span></span><span></span><span>15</span><span></span><span></span><span>18</span><span></span><span></span><span>21</span><span></span><span></span><span>24</span></div>').insertBefore("#periodInput") }), fieldMax = $("#parapagimi").attr("max"), minimumiKredise = 5e3, maximumiKredise = 1e5, firstAmountValue = $("#xxamount").val(), $("#parapagimi").on("input", function () { currentValue = $("#parapagimi").val(), fieldMax = $("#parapagimi").attr("max"), currentValue > fieldMax ? ($("#parapagimi").val(fieldMax), firstAmountValue - fieldMax < minimumiKredise ? ($(".custom-slider-holder .xxiute-calc-single .xxrange-slider__value").val(minimumiKredise), $("#vleraForm").val(minimumiKredise)) : ($(".custom-slider-holder .xxiute-calc-single .xxrange-slider__value").val(firstAmountValue - fieldMax), $("#vleraForm").val(firstAmountValue - fieldMax))) : firstAmountValue - currentValue < minimumiKredise ? ($(".custom-slider-holder .xxiute-calc-single .xxrange-slider__value").val(minimumiKredise), $("#vleraForm").val(minimumiKredise)) : ($(".custom-slider-holder .xxiute-calc-single .xxrange-slider__value").val(firstAmountValue - currentValue), $("#vleraForm").val(firstAmountValue - currentValue)), iuteCalculateLoan() }), currMonths = parseInt($(" .xxiute-calc-single time span").html()), currAmount = $(" #vleraForm").val(), $(document).ready(function () { $("#hero-slider").slick({ arrows: !1, dots: !0, autoplay: !0, slidesToShow: 1, slidesToScroll: 1, fade: !0 }), sku = $(".sku").html(), $("#skuForm").val(sku), $("#skuForm2").val(sku), $("#loan-form").validate({ onkeyup: !1, messages: { emer: "Ju lutemi vendosni emrin tuaj.", mbiemer: "Ju lutemi vendosni mbiemrin tuaj.", birth: "Ju lutemi vendosni datëlindjen tuaj", email: "Ju lutemi vendosni email-in tuaj.", phone: "Ju lutemi vendosni saktë numrin tuaj të celularit.", idCard: "Ju lutemi vendosni numrin e kartës me 10 shifra fillon dhe mbaron me gërmë.", address: "Ju lutemi vendosni adresën tuaj.", city: "Ju lutemi vendosni qytetin tuaj.", terms: "Duhet të pranoni termat dhe kushtet e proçesimit të të dhënave." }, rules: { emer: "required", email: { required: !0, email: !0 }, idCard: { minlength: 10 }, phone: { required: !0, digits: !0 } } }), $("#one-click-form").validate({ onkeyup: !1, messages: { emer2: "Ju lutemi vendosni emrin tuaj.", mbiemer2: "Ju lutemi vendosni mbiemrin tuaj.", email2: "Ju lutemi vendosni email-in tuaj.", phone2: "Ju lutemi vendosni saktë numrin tuaj të celularit.", address2: "Ju lutemi vendosni adresën tuaj.", city2: "Ju lutemi vendosni qytetin tuaj." }, rules: { emer2: "required", email2: { required: !0, email: !0 }, phone2: { required: !0, digits: !0 } } }) }), $.extend($.validator.messages, { required: "Fusha duhet plotësuar.", remote: "Please fix this field.", email: "Ju lutem vendosni një email të vlefshëm.", number: "Ju lutem vendosni një numër të vlefshëm.", digits: "Ju lutem vendosni vetëm numra.", creditcard: "Ju lutem vendosni a valid credit card number.", equalTo: "Ju lutem vendosni the same value again.", accept: "Ju lutem vendosni a value with a valid extension.", maxlength: $.validator.format("Ju lutem vendosni jo më shumë se {0} karaktere."), minlength: $.validator.format("Ju lutem vendosni jo më pak se {0} karaktere."), rangelength: $.validator.format("Ju lutem vendosni një vlere midis {0} dhe {1} karakteresh."), range: $.validator.format("Ju lutem vendosni një vlere midis {0} dhe {1}."), max: $.validator.format("Ju lutem vendosni një vlere më të vogël ose të barabartë me {0}."), min: $.validator.format("Ju lutem vendosni një vlere më të madhe ose të barabartë me {0}.") });