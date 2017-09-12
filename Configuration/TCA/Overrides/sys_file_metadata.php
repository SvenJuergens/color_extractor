<?php
defined('TYPO3_MODE') || die();
$tmp_color_extractor_columns = [];

foreach ([1,2,3,4,5] as $item){
    $tmp_color_extractor_columns['tx_colorextractor_color' . $item] = [
        'exclude' => true,
        'label' => 'LLL:EXT:color_extractor/Resources/Private/Language/locallang_db.xlf:tx_colorextractor_domain_model_sysfilemetadata.tx_colorextractor_color' . $item,
        'config' => [
            'type' => 'input',
            'size' => 6,
            'eval' => 'trim',
            'wizards' => [
                'colorChoice' => [
                    'type' => 'colorbox',
                    'module' => [
                        'name' => 'wizard_colorpicker',
                    ],
                ]
            ]
        ],
    ];
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_metadata',$tmp_color_extractor_columns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_metadata',
    'tx_colorextractor_color1,tx_colorextractor_color2,tx_colorextractor_color3,tx_colorextractor_color4,tx_colorextractor_color5',
    ''
);