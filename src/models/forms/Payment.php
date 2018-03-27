<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace craft\commerce\stripe\models\forms;

use craft\commerce\models\payments\CreditCardPaymentForm;
use craft\commerce\models\PaymentSource;
use craft\commerce\stripe\Plugin;

/**
 * Stripe Payment form model.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 1.0
 */
class Payment extends CreditCardPaymentForm
{
    /**
     * @var string $customer the Stripe customer token.
     */
    public $customer;

    // Public methods
    // =========================================================================
    /**
     * @inheritdoc
     */
    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);

        if (isset($values['stripeToken'])) {
            $this->token = $values['stripeToken'];
        }
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [[['token'], 'required']];
    }

    /**
     * @inheritdoc
     */
    public function populateFromPaymentSource(PaymentSource $paymentSource)
    {
        $this->token = $paymentSource->token;

        $customer = Plugin::getInstance()->getCustomers()->getCustomer($paymentSource->gatewayId, $paymentSource->getUser());
        $this->customer = $customer->reference;
    }
}
