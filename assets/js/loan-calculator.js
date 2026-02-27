(function ($) {
    'use strict';

    const COUNTRY_CURRENCY_MAP = {
        US: { currency: 'USD', locale: 'en-US', label: 'USD ($)' },
        GB: { currency: 'GBP', locale: 'en-GB', label: 'GBP (£)' },
        EU: { currency: 'EUR', locale: 'de-DE', label: 'EUR (€)' },
        CA: { currency: 'CAD', locale: 'en-CA', label: 'CAD (C$)' },
        AU: { currency: 'AUD', locale: 'en-AU', label: 'AUD (A$)' },
        IN: { currency: 'INR', locale: 'en-IN', label: 'INR (₹)' },
        JP: { currency: 'JPY', locale: 'ja-JP', label: 'JPY (¥)' },
        MY: { currency: 'MYR', locale: 'ms-MY', label: 'MYR (RM)' },
        SG: { currency: 'SGD', locale: 'en-SG', label: 'SGD (S$)' },
    };

    function getCurrencyConfig(countryCode) {
        return COUNTRY_CURRENCY_MAP[countryCode] || COUNTRY_CURRENCY_MAP.US;
    }

    function formatCurrency(value, countryCode) {
        const config = getCurrencyConfig(countryCode);
        return new Intl.NumberFormat(config.locale, {
            style: 'currency',
            currency: config.currency,
            maximumFractionDigits: 2,
        }).format(value);
    }

    function calculateLoan(amount, annualRate, years) {
        const monthlyRate = annualRate / 100 / 12;
        const months = years * 12;

        if (months <= 0 || amount <= 0 || Number.isNaN(monthlyRate)) {
            return null;
        }

        if (monthlyRate === 0) {
            const monthlyPayment = amount / months;
            const totalPayment = monthlyPayment * months;
            return {
                monthlyPayment,
                totalPayment,
                totalInterest: 0,
            };
        }

        const factor = Math.pow(1 + monthlyRate, months);
        const monthlyPayment = (amount * monthlyRate * factor) / (factor - 1);
        const totalPayment = monthlyPayment * months;
        const totalInterest = totalPayment - amount;

        return {
            monthlyPayment,
            totalPayment,
            totalInterest,
        };
    }

    function updateResults($calculator) {
        const amount = parseFloat($calculator.find('.lce-amount').val());
        const rate = parseFloat($calculator.find('.lce-rate').val());
        const years = parseFloat($calculator.find('.lce-years').val());
        const country = $calculator.find('.lce-country').val() || 'US';
        const currencyConfig = getCurrencyConfig(country);

        $calculator.find('.lce-currency-code').text(currencyConfig.label);

        const result = calculateLoan(amount, rate, years);

        if (!result) {
            $calculator.find('.lce-monthly-payment').text('Invalid input');
            $calculator.find('.lce-total-payment').text('Invalid input');
            $calculator.find('.lce-total-interest').text('Invalid input');
            return;
        }

        $calculator.find('.lce-monthly-payment').text(formatCurrency(result.monthlyPayment, country));
        $calculator.find('.lce-total-payment').text(formatCurrency(result.totalPayment, country));
        $calculator.find('.lce-total-interest').text(formatCurrency(result.totalInterest, country));
    }

    function bindCalculator($calculator) {
        $calculator.each(function () {
            const $current = $(this);

            if ($current.data('lce-initialized')) {
                return;
            }

            $current.data('lce-initialized', true);

            $current.find('.lce-calculate-button').on('click', function () {
                updateResults($current);
            });

            $current.find('.lce-amount, .lce-rate, .lce-years, .lce-country').on('input change', function () {
                updateResults($current);
            });

            updateResults($current);
        });
    }

    function initStandalone() {
        bindCalculator($('.lce-loan-calculator'));
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            bindCalculator($scope.find('.lce-loan-calculator'));
        });
    });

    $(document).ready(function () {
        initStandalone();
    });
})(jQuery);
