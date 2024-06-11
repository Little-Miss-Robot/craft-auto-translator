<?php

namespace Lmr\AutoTranslator;

use Craft;
use craft\base\{Event, Model, Plugin as BasePlugin};
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\helpers\UrlHelper;
use craft\services\UserPermissions;
use craft\web\UrlManager;

use Lmr\AutoTranslator\Contracts\FieldResolverInterface;
use Lmr\AutoTranslator\Contracts\PolicyInterface;
use Lmr\AutoTranslator\Contracts\TranslationServiceInterface;
use Lmr\AutoTranslator\Fields\Resolver;
use Lmr\AutoTranslator\Models\Settings;
use Lmr\AutoTranslator\Translator\Translator;
use Lmr\AutoTranslator\Services\FieldService;

class Plugin extends BasePlugin
{
    public static $instance;

    /**
     * @var string $schemaVersion
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool $hasCpSettings
     */
    public bool $hasCpSettings = true;

    /**
     * @var bool $hasCpSection
     */
    public bool $hasCpSection = true;

    /**
     * @return array[]
     */
    public static function config(): array
    {
        return [
            'components' => [
                'translator' => [
                    'class' => Translator::class,
                ],
            ],
        ];
    }


    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        self::$instance = $this;

        Craft::$app->onInit(function() {
            $this->bindDependencies();
            $this->registerComponents();
            $this->attachEventHandlers();

            if (Craft::$app->getRequest()->getIsCpRequest()) {
                $this->registerCpUrlRules();
            }
        });
    }

    public function getSettingsResponse(): mixed
    {
        // Redirect our standard settings screen in the settings admin page to custom settings screen
        return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('auto-translator/plugin-settings'));
    }

    public function getCpNavItem(): ?array
    {
        $canEditSettings = true;
        $general = Craft::$app->getConfig()->getGeneral();
        if (!$general->allowAdminChanges) {
            $canEditSettings = false;
        }

        $currentUser = Craft::$app->getUser()->getIdentity();

        $nav = parent::getCpNavItem();
        $nav['label'] = Craft::t('auto-translator', 'Auto Translator');

        $nav['subnav']['content'] = [
            'label' => Craft::t('auto-translator', 'nav.content-settings'),
            'url' => 'auto-translator/content-settings'
        ];

        if ($canEditSettings && $currentUser->can('auto-translator:edit-plugin-settings')) {
            $nav['subnav']['plugin'] = [
                'label' => Craft::t('auto-translator', 'nav.plugin-settings'),
                'url' => 'auto-translator/plugin-settings',
            ];
        }

        return $nav;
    }

    /**
     * @return void
     */
    private function bindDependencies(): void
    {
        $config = $this->getSettings();

        Craft::$container->set(FieldResolverInterface::class, Resolver::class);
        Craft::$container->set(PolicyInterface::class, $config->policy);
        Craft::$container->set(TranslationServiceInterface::class, $config->services[$config->service]);
    }

    /**
     * @return void
     */
    private function registerComponents(): void
    {
        $this->setComponents([
			'fieldService' => \Lmr\AutoTranslator\Services\FieldService::class,
		]);
    }

    /**
     * @return void
     */
    private function attachEventHandlers(): void
    {
        Event::on(Entry::class, Entry::EVENT_AFTER_PROPAGATE, function (ModelEvent $event) {

            $config = $this->getSettings();

            $entry = $event->sender;

            $translatableSections = $config->translate;
            $sectionHandle = $entry->section->handle;
            $entryTypeHandle = $entry->type->handle;

            $identifier = Plugin::getInstance()->fieldService->getIdentifierForSection($sectionHandle, $entryTypeHandle);

            if (! $config->enabled || ! isset($translatableSections[$identifier])) {
                return;
            }

            $this->translator->queueTranslation($event->sender);
        });
    }

    /**
     * @return void
     */
    private function registerCpUrlRules(): void
    {
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['auto-translator'] = 'auto-translator/base/index';
                $event->rules['auto-translator/content-settings'] = 'auto-translator/base/content';
                $event->rules['auto-translator/plugin-settings'] = 'auto-translator/base/plugin';
            }
        );

        // Handler: UserPermissions::EVENT_REGISTER_PERMISSIONS
		Event::on(
			UserPermissions::class,
			UserPermissions::EVENT_REGISTER_PERMISSIONS,
			function (RegisterUserPermissionsEvent $event) {
				Craft::debug(
					'UserPermissions::EVENT_REGISTER_PERMISSIONS',
					__METHOD__
				);

				// Register our custom permissions
				$event->permissions[] = [
					'heading' => Craft::t('auto-translator', 'Auto Translator'),
					'permissions' => $this->customAdminCpPermissions(),
				];
			}
		);
    }

    /**
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * @return array
     */
    protected function customAdminCpPermissions(): array
	{
		return [
			'auto-translator:edit-plugin-settings' => [
				'label' => Craft::t('auto-translator', 'permissions.edit-plugin-settings'),
			]
		];
	}
}
