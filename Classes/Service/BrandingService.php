<?php

/*
 * This file is part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace BK2K\BootstrapPackage\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Package\Event\AfterPackageActivationEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * BrandingService
 */
class BrandingService
{
    /**
     * @var string
     */
    protected const EXT_KEY = 'bootstrap_package';

    public function __invoke(AfterPackageActivationEvent $event): void
    {
        $this->setBackendStyling($event->getPackageKey());
    }

    /**
     * @param string $extension
     */
    public function setBackendStyling($extension = null)
    {
        if ($extension === self::EXT_KEY && class_exists(ExtensionConfiguration::class)) {
            $extensionConfiguration = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            );
            $backendConfiguration = $extensionConfiguration->get('backend');

            if (!isset($backendConfiguration['loginLogo']) || empty(trim($backendConfiguration['loginLogo']))) {
                $backendConfiguration['loginLogo'] = 'EXT:bootstrap_package/Resources/Public/Images/Backend/login-logo.svg';
            }
            if (!isset($backendConfiguration['loginBackgroundImage']) || empty(trim($backendConfiguration['loginBackgroundImage']))) {
                $backendConfiguration['loginBackgroundImage'] = 'EXT:bootstrap_package/Resources/Public/Images/Backend/login-background-image.jpg';
            }
            if (!isset($backendConfiguration['backendLogo']) || empty(trim($backendConfiguration['backendLogo']))) {
                $backendConfiguration['backendLogo'] = 'EXT:bootstrap_package/Resources/Public/Images/Backend/backend-logo.svg';
            }

            $reflection = new \ReflectionClass(ExtensionConfiguration::class);
            $parameters = $reflection->getMethod('set')->getParameters();

            if (count($parameters) === 3) {
                $extensionConfiguration->set('backend', '', $backendConfiguration);
            } else {
                $extensionConfiguration->set('backend', $backendConfiguration);
            }
        }
    }
}
