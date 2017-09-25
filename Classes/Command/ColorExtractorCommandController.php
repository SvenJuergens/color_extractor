<?php
namespace SvenJuergens\ColorExtractor\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use SvenJuergens\ColorExtractor\Services\Extraction\ColorMetadataExtraction;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;


/**
 * Language command controller updates translation packages
 */
class ColorExtractorCommandController extends CommandController
{
    /**
     * @var \SvenJuergens\ColorExtractor\Services\Extraction\ColorMetadataExtraction;
     */
    protected $colorExtractorService;

    /**
     * @param \SvenJuergens\ColorExtractor\Services\Extraction\ColorMetadataExtraction $colorExtractorService
     */
    public function injectColorExtractorService(ColorMetadataExtraction $colorExtractorService)
    {
        $this->colorExtractorService = $colorExtractorService;
    }


    /**
     * Update each folder
     * @param int $max Max images in one loop
     * @param string $foldersToUpdate Comma separated list of Folders that needs to be updated
     */
    public function updateCommand($max = 10, $foldersToUpdate = '')
    {
        if (!empty($foldersToUpdate)) {
            // Check for valid folders
            $folders = GeneralUtility::trimExplode(
                ',',
                $foldersToUpdate,
                true
            );
            foreach ($folders as $folder) {
                $resourceFactory = ResourceFactory::getInstance();
                $defaultStorage = $resourceFactory->getDefaultStorage();
                $folder = $defaultStorage->getFolder(str_replace('fileadmin', '', $folder));
                /** @var File $files */
                $files = $defaultStorage->getFilesInFolder($folder);
                foreach ($files as $file){
                    $this->callColorExtractorServiceOnFile($file);
                }
            }
        }
    }

    public function callColorExtractorServiceOnFile($file)
    {
        DebugUtility::debug($file, __FILE__ . __LINE__);
        if($this->colorExtractorService->canProcess($file)){
            $this->colorExtractorService->extractMetaData($file);
        }
    }
}
