<?php

namespace Lighthouse\IntegrationBundle\Set10\Import\Sales;

use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\IntegrationBundle\Set10\Import\Products\Set10Import;
use Lighthouse\IntegrationBundle\Util\SortableDirectoryIterator;
use Lighthouse\IntegrationBundle\Util\Url;
use JMS\DiExtraBundle\Annotation as DI;
use SplFileInfo;

/**
 * @DI\Service("lighthouse.integration.set10.import_sales.remote_directory")
 */
class RemoteDirectory
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * @var string
     */
    protected $dirUrl;

    /**
     * @var string
     */
    protected $filePrefix = 'purchases-';

    /**
     * @DI\InjectParams({
     *      "configRepository" = @DI\Inject("lighthouse.core.document.repository.config")
     * })
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @throws RuntimeException
     * @return \SplFileInfo[]
     */
    public function read()
    {
        $files = array();
        $dirUrl = $this->getDirUrl();
        try {
            $directory = new SortableDirectoryIterator($dirUrl);
        } catch (\UnexpectedValueException $e) {
            throw new RuntimeException(sprintf('Failed to read directory "%s": %s', $dirUrl, $e->getMessage()));
        }
        if (!$directory->getFileInfo()->isDir()) {
            throw new RuntimeException(sprintf('Failed to read directory "%s": It is not a directory', $dirUrl));
        }
        $directory->sortByFilename(true);
        /* @var \DirectoryIterator $file */
        foreach ($directory as $file) {
            if ($file->isFile() && $this->isValidFile($file)) {
                $files[] = new SplFileInfo($file->getPathname());
            }
        }
        return $files;
    }

    /**
     * @param \SplFileInfo $file
     * @return bool
     */
    protected function isValidFile(\SplFileInfo $file)
    {
        if ('xml' != $file->getExtension()) {
            return false;
        } elseif (null !== $this->filePrefix && 0 !== strpos($file->getFilename(), $this->filePrefix)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath)
    {
        if ($filePath instanceof \SplFileInfo) {
            $filePath = $filePath->getPathname();
        }
        return unlink($filePath);
    }

    /**
     * @return string
     */
    public function getDirUrl()
    {
        if (null === $this->dirUrl) {
            $dirUrl = new Url($this->configRepository->findValueByName(Set10Import::URL_CONFIG_NAME));
            $dirUrl->setPart(Url::USER, $this->configRepository->findValueByName(Set10Import::LOGIN_CONFIG_NAME));
            $dirUrl->setPart(Url::PASS, $this->configRepository->findValueByName(Set10Import::PASSWORD_CONFIG_NAME));
            $this->dirUrl = $dirUrl->toString();
        }
        return $this->dirUrl;
    }
}
