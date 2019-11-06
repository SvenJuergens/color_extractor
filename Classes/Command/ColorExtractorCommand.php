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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Language command controller updates translation packages
 */
class ColorExtractorCommand extends Command
{

    /**
     * @var ColorMetadataExtraction;
     */
    protected $colorExtractorService;

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this
            ->setDescription('Update image metadata with color information')
            ->addArgument(
                'max',
                InputArgument::OPTIONAL,
                'Max images in one loop',
                50
            )
            ->addArgument(
                'foldersToUpdate',
                InputArgument::OPTIONAL,
                'Comma separated list of Folders that needs to be updated',
                'fileadmin/images'
            );
    }


    /**
     * Update all MetaData of Images with color Information
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws InsufficientFolderAccessPermissionsException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $max = $input->getArgument('max');
        $foldersToUpdate = $input->getArgument('foldersToUpdate');

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
            $counter = 0;
            foreach ($files as $file) {
                if ($counter >= $max) {
                    break;
                }
                $fileMeta = $file->getProperties();
                if (isset($fileMeta['tx_colorextractor_color1'])
                    && empty($fileMeta['tx_colorextractor_color1'])) {
                    $this->callColorExtractorServiceOnFile($file);
                    $counter++;
                }
            }
        }
    }


    /**
     * @param File $file
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function callColorExtractorServiceOnFile(File $file): void
    {
        if ($this->getColorExtractorService()->canProcess($file)) {
            $metaData = $this->getColorExtractorService()->extractMetaData($file);
            MetaDataRepository::getInstance()->update($file->getUid(), $metaData);
        }
    }

    /**
     * @return object|ColorMetadataExtraction
     */
    protected function getColorExtractorService()
    {
        if($this->colorExtractorService === null){
            $this->colorExtractorService = GeneralUtility::makeInstance(ColorMetadataExtraction::class);
        }
        return $this->colorExtractorService;
    }

}
