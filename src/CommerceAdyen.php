<?php
/**
 * Commerce Adyen plugin for Craft CMS 3.x
 *
 * Adyen integration for Craft Commerce 2 and 3
 *
 * @link      https://bluemantis.com
 * @copyright Copyright (c) 2020 Bluemantis
 */

namespace bluemantis\commerceadyen;


use bluemantis\commerceadyen\gateways\HostedPaymentPages;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\commerce\services\Gateways;
use yii\base\Event;

/**
 * Class CommerceAdyen
 *
 * @author    Bluemantis
 * @package   CommerceAdyen
 * @since     0.1
 *
 */
class CommerceAdyen extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CommerceAdyen
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '0.1';

    /**
     * @var bool
     */
    public $hasCpSettings = false;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->registerEvents();

        Craft::info(
            Craft::t(
                'commerce-adyen',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    protected function registerEvents()
    {
        Event::on(Gateways::class, Gateways::EVENT_REGISTER_GATEWAY_TYPES,  function(RegisterComponentTypesEvent $event) {
            $event->types[] = HostedPaymentPages::class;
        });
    }
}
