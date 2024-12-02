<?php
namespace littlemissrobot\autotranslator\controllers;

use Craft;
use yii\web\Response;
use craft\web\Controller;
use littlemissrobot\autotranslator\Plugin;

class SettingsController extends Controller
{
    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $url = "auto-translator/main-settings";

        return $this->redirect($url);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionMain()
    {
        $config = Plugin::getInstance()->getSettings();
        $services = $config->services;

        $serviceSelect = array_combine(array_keys($services), array_map(function ($value) {
            return ucfirst($value);
        }, array_keys($services)));

        $languages = array_unique(array_map(function ($site) {
            return $site->language;
        }, Craft::$app->sites->getAllSites()));

        return $this->renderTemplate(
            'auto-translator/main-settings',
            [
                'settings' => $config,
                'serviceOptions' => $serviceSelect,
                'languageOptions' => array_combine($languages, $languages),
            ],
        );
    }

    /**
     * @return Response|null
     * @throws \craft\errors\MissingComponentException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveMainSettings(): ?Response
    {
        $this->requirePostRequest();

        $plugin = Craft::$app->getPlugins()->getPlugin('auto-translator');
        $settings = Craft::$app->getRequest()->getBodyParam('settings', []);

        $plugin->getSettings()->setAttributes($settings, false);

        if (Craft::$app->plugins->savePluginSettings($plugin, $plugin->getSettings()->toArray())) {

            Craft::$app->getSession()->setNotice(Craft::t('auto-translator', 'Settings saved.'));
            return $this->redirectToPostedUrl();
        }

        return null;
    }

    /**
     * @return Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionContent() {
        $config = Plugin::getInstance()->getSettings();
        $fieldInfo = Craft::$container->get('fieldService')->getElementInfo();

        return $this->renderTemplate('auto-translator/content-settings', [
            'settings' => $config,
            'fieldInfo' => $fieldInfo,
        ]);
    }

    /**
     * @return Response|null
     * @throws \craft\errors\MissingComponentException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveContentSettings(): ?Response
    {
        $this->requirePostRequest();

        $plugin = Craft::$app->getPlugins()->getPlugin('auto-translator');
        $settings = Craft::$app->getRequest()->getBodyParam('settings', []);

        $plugin->getSettings()->setAttributes($settings, false);

        if (Craft::$app->plugins->savePluginSettings($plugin, $plugin->getSettings()->toArray())) {

            Craft::$app->getSession()->setNotice(Craft::t('auto-translator', 'Settings saved.'));
            return $this->redirectToPostedUrl();
        }

        return null;
    }
}
