<?php

namespace bluemantis\commerceadyen\gateways;

use Craft;
use craft\commerce\base\RequestResponseInterface;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\Transaction;
use craft\commerce\records\Transaction as TransactionRecord;
use craft\commerce\omnipay\base\OffsiteGateway;
use craft\web\Response;
use Omnipay\Common\AbstractGateway;
use Omnipay\Adyen\HppGateway;

/**
 * Class HostedPaymentPages
 *
 * @author    Bluemantis
 * @package   CommerceAdyen
 * @since     0.1
 *
 */
class HostedPaymentPages extends OffsiteGateway
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $secret;

    /**
     * @var string
     */
    public $skinCode;

    /**
     * @var string
     */
    public $merchantAccount;

    /**
     * @var string
     */
    public $publicKeyToken;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $currency = 'GBP';

    /**
     * @var string
     */
    public $countryCode = 'GB';

    /**
     * @var bool
     */
    public $testMode = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('commerce', 'Ayden Hosted Payment Pages');
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('commerce-adyen/gatewaySettings', ['gateway' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function populateRequest(array &$request, BasePaymentForm $paymentForm = null)
    {
        parent::populateRequest($request, $paymentForm);
    }

    /**
     * @inheritdoc
     */
    /*public function supportsWebhooks(): bool
    {
        return true;
    }

    public function processWebHook(): Response
    {
        $response = Craft::$app->getResponse();

        $gateway = $this->createGateway();
        $request = $gateway->acceptNotification();

        return $response;
    }*/

    /**
     * A custom refund method is required as all refunds need to reference the original AUTHORIZATION transaction,
     *  instead of whatever transaction is passed here
     *
     * @inheritdoc
     */
    public function refund(Transaction $transaction): RequestResponseInterface
    {
        $authorizationTransaction = $this->getAuthorizeTransaction($transaction);
        $request = $this->createRequest($authorizationTransaction);
        $refundRequest = $this->prepareRefundRequest($request, $authorizationTransaction->reference);

        return $this->performRequest($refundRequest, $authorizationTransaction);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Find an AUTHORIZATION transaction from a transaction and its parents
     *
     * @param \craft\commerce\models\Transaction $transaction
     * @return \craft\commerce\models\Transaction
     */
    protected function getAuthorizeTransaction(Transaction $transaction)
    {
        $authorizationTransaction = $this->getTransactionByType($transaction, TransactionRecord::TYPE_AUTHORIZE);
        return ($authorizationTransaction ? $authorizationTransaction : $transaction);
    }

    /**
     * Loop through a transaction and it's parents to find a transaction by its type
     *
     * @param \craft\commerce\models\Transaction $transaction
     * @param string $type
     * @return \craft\commerce\models\Transaction|null
     */
    protected function getTransactionByType(Transaction $transaction, $type)
    {
        if ($transaction->type == $type) {
            return $transaction;
        } else {
            $parent = $transaction->getParent();
            return ($parent ? $this->getTransactionByType($parent, $type) : null);
        }
    }

    /**
     * @inheritdoc
     */
    protected function createGateway(): AbstractGateway
    {
        /** @var HppGateway $gateway */
        $gateway = static::createOmnipayGateway($this->getGatewayClassName());

        $gateway->initialize([
            'secret' => $this->secret,
            'skinCode' => $this->skinCode,
            'merchantAccount' => $this->merchantAccount,
            'testMode' => $this->testMode,
            'currency' => $this->currency,
            'countryCode' => $this->countryCode,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        return $gateway;
    }

    /**
     * @inheritdoc
     */
    protected function getGatewayClassName()
    {
        return '\\'.HppGateway::class;
    }
}
