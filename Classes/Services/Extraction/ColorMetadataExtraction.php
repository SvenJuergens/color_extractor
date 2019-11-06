<?php
namespace SvenJuergens\ColorExtractor\Services\Extraction;

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

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Index\ExtractorInterface;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A service to extract color metadata from files
 */
class ColorMetadataExtraction implements ExtractorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var int
     */
    protected $priority = 50;

    /** @var array */
    protected $supportedFileExtensions = [
        'gif',      // IMAGETYPE_GIF
        'jpg',      // IMAGETYPE_JPEG
        'jpeg',     // IMAGETYPE_JPEG
        'png',      // IMAGETYPE_PNG
        'bmp',      // IMAGETYPE_BMP
        'tif',      // IMAGETYPE_TIFF_II / IMAGETYPE_TIFF_MM
        'tiff',     // IMAGETYPE_TIFF_II / IMAGETYPE_TIFF_MM
    ];

    /**
     * @var array
     */
    protected $supportedFileTypes = [];

    /**
     * Returns an array of supported file types;
     * An empty array indicates all filetypes
     *
     * @return array
     */
    public function getFileTypeRestrictions(): array
    {
        return $this->supportedFileTypes;
    }

    /**
     * Get all supported DriverClasses
     *
     * Since some extractors may only work for local files, and other extractors
     * are especially made for grabbing data from remote.
     *
     * Returns array of string with driver names of Drivers which are supported,
     * If the driver did not register a name, it's the classname.
     * empty array indicates no restrictions
     *
     * @return array
     */
    public function getDriverRestrictions(): array
    {
        return [
            'Local',
        ];
    }

    /**
     * Returns the data priority of the extraction Service.
     * Defines the precedence of Data if several extractors
     * extracted the same property.
     *
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority(): int
    {
        return max(1, min(100, $this->priority));
    }

    /**
     * Returns the execution priority of the extraction Service
     * Should be between 1 and 100, 100 means runs as first service, 1 runs at last service
     *
     * @return int
     */
    public function getExecutionPriority(): int
    {
        return $this->getPriority();
    }

    /**
     * Checks if the given file can be processed by this extractor.
     *
     * @param File $file
     * @return bool
     */
    public function canProcess(File $file): bool
    {
        $fileExtension = strtolower($file->getProperty('extension'));
        return in_array($fileExtension, $this->supportedFileExtensions);
    }

    /**
     * The actual processing task.
     *
     * Should return an array with database properties for sys_file_metadata to write.
     *
     * @param File $file
     * @param array $previousExtractedData optional, contains the array of already extracted data
     * @return array
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function extractMetaData(File $file, array $previousExtractedData = []): array
    {
        $sizeParts = [200, 200];
        $processedImage = $file->process(
            ProcessedFile::CONTEXT_IMAGECROPSCALEMASK,
            [
                'width' => $sizeParts[0],
                'height' => $sizeParts[1],
                'crop' => null
            ]
        );
        $colors = self::extractFromFile($processedImage);
        foreach ($colors as &$color) {
            $color = Color::fromIntToHex($color);
        }
        unset($color);
        $metadata = [
            'tx_colorextractor_color1' => $colors[0],
            'tx_colorextractor_color2' => $colors[1],
            'tx_colorextractor_color3' => $colors[2],
            'tx_colorextractor_color4' => $colors[3],
            'tx_colorextractor_color5' => $colors[4],
        ];
        if ((bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('color_extractor', 'useLogging')
        ) {
            $this->logger->info('color_extractor', [
                'file Extraction' => $file->getPublicUrl(),
                'colors' => implode(',', $colors ?? [])

            ]);
        }
        return $metadata;
    }
    /**
     * @param File|ProcessedFile $file
     * @return array
     * @throws \Error
     */
    public static function extractFromFile($file): array
    {
        if (!is_callable([$file, 'getPublicUrl'])) {
            throw new \Error('huhu');
        }
        $palette = Palette::fromFilename(GeneralUtility::getFileAbsFileName($file->getPublicUrl()));
        $extractor = new ColorExtractor($palette);
        return $extractor->extract(5);
    }
}
