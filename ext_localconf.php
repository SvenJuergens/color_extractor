<?php
defined('TYPO3_MODE') || die('Access denied.');

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('color_extractor');
if ( !class_exists('League\\ColorExtractor\\Color')
    || !class_exists('League\\ColorExtractor\\ColorExtractor')
    || !class_exists('League\\ColorExtractor\\Palette')
) {
    require_once 'phar://' . $extPath . '/Resources/Private/Php/color-extractor.phar/vendor/autoload.php';
}

$extractorRegistry = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
$extractorRegistry->registerExtractionService(
    \SvenJuergens\ColorExtractor\Services\Extraction\ColorMetadataExtraction::class
);

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers']['color_extractor'] =
        \SvenJuergens\ColorExtractor\Command\ColorExtractorCommandController::class;
}
