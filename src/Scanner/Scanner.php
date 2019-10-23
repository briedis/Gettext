<?php
declare(strict_types = 1);

namespace Gettext\Scanner;

use Gettext\Translations;
use Gettext\Translation;
use Exception;

/**
 * Base class with common funtions for all scanners
 */
abstract class Scanner implements ScannerInterface
{
    protected $translations;
    protected $defaultDomain;

    public function __construct(Translations ...$allTranslations)
    {
        $this->setTranslations(...$allTranslations);
    }

    public function setDefaultDomain(string $defaultDomain): void
    {
        $this->defaultDomain = $defaultDomain;
    }

    public function getDefaultDomain(): string
    {
        return $this->defaultDomain;
    }

    public function setTranslations(Translations ...$allTranslations): void
    {
        foreach ($allTranslations as $translations) {
            $domain = $translations->getDomain();
            $this->translations[$domain] = $translations;
        }
    }

    public function getTranslations(): array
    {
        return array_values($this->translations);
    }

    public function scanFile(string $filename): void
    {
        $string = static::readFile($filename);

        $this->scanString($string, $filename);
    }

    public function scanString(string $string, string $filename = null): void
    {
    }

    protected function saveTranslation(?string $domain, ?string $context, string $original, string $plural = null): ?Translation
    {
        if (is_null($domain)) {
            $domain = $this->defaultDomain;
        }

        if (!isset($this->translations[$domain])) {
            return null;
        }

        $translation = Translation::create($context, $original);

        $this->translations[$domain]->add($translation);

        if (isset($plural)) {
            $translation->setPlural($plural);
        }

        return $translation;
    }

    /**
     * Reads and returns the content of a file.
     */
    protected static function readFile(string $file): string
    {
        $length = filesize($file);

        if (!($fd = fopen($file, 'rb'))) {
            throw new Exception("Cannot read the file '$file', probably permissions");
        }

        $content = $length ? fread($fd, $length) : '';
        fclose($fd);

        return $content;
    }
}