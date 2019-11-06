<?php
defined('TYPO3_MODE') || die('Access denied.');

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('color_extractor');
if (!class_exists('League\\ColorExtractor\\Color')
    || !class_exists('League\\ColorExtractor\\ColorExtractor')
    || !class_exists('League\\ColorExtractor\\Palette')
) {
    require_once 'phar://' . $extPath . 'Resources/Private/Php/color-extractor.phar/vendor/autoload.php';
}

$extractorRegistry = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
$extractorRegistry->registerExtractionService(
    \SvenJuergens\ColorExtractor\Services\Extraction\ColorMetadataExtraction::class
);


$GLOBALS['TYPO3_CONF_VARS']['LOG']['SvenJuergens']['ColorExtractor']['writerConfiguration'] = [
    \TYPO3\CMS\Core\Log\LogLevel::INFO => [
        \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
            'logFileInfix' => 'colorExtractor'
        ]
    ]
];
