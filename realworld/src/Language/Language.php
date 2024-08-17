<?php

namespace App\Language;

/**
 * Handle system messages and localization.
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
class Language
{
    /**
     * The current language/locale to work with.
     * @var string
     */
    protected $locale = 'en';

    //Путь к директории с файлами переводов
    protected $filesDirPath =  __DIR__ . '/../langs/';

    //загруженные переводы
    protected $translates = [];

    public function __construct()
    {

    }

    public static function getInstance(): Language
    {
        static $instance;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }

    // /**
    //  * Sets the current locale to use when performing string lookups.
    //  */
    // public function setLocale(?string $locale)
    // {
    //     if ($locale !== null) {
    //         $this->locale = $locale;
    //     }

    //     return $this;
    // }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Parses the language string for a file, loads the file, if necessary,
     * getting the line.
     *
     * @return string
     */
    public function getLine(string $line, array $args = [], ?string $locale = null)
    {
        // Parse out the file name and the actual alias.
        // Will load the language file and strings.
        [$file, $parsedLine] = $this->parseLine($line);

        // Get active locale
        $activeLocale = $this->getLocale();

        $output = $this->getTranslationOutput($this->locale, $file, $parsedLine);

        $output = $this->formatMessage($output ?? $parsedLine, $args);

        return $output;
    }

    /**
     * @return array|string|null
     */
    protected function getTranslationOutput(string $locale, string $file, string $parsedLine)
    {
        if (!isset($this->translates[$locale][$file])) {
            $this->load($file, $locale);
        }

        // //TODO на время добавления мультиязычности. автоматически добавляем перевод в файл
        // if (!isset($this->translates[$locale][$file][$parsedLine]) && $locale == 'ru' && is_development()) {
        //     $this->translates[$locale][$file][$parsedLine] = $parsedLine;
        //     $this->save($file, $locale, $parsedLine);
        // }

        return $this->translates[$locale][$file][$parsedLine] ?? null;
    }

    /**
     * example:   filename.Text
     */
    protected function parseLine(string $line): array
    {
        if (strpos($line, '.') === false) {
            $file = 'default';
        } else {
            list($file, $line) = explode('.', $line, 2);
        }

        $file = str_replace('/', '_', $file);
        $line = str_replace("\r", "", $line);

        return [trim($file), trim($line)];
    }

    /**
     * Advanced message formatting.
     *
     * @param array|string $message
     * @param string[]     $args
     *
     * @return array|string
     */
    protected function formatMessage($message, array $args = [])
    {
        if ($message && $args) {
            foreach ($args as $k => $v) {
                $message = str_replace("{{$k}}", $v, $message);
            }
        }

        return $message;
    }

    /**
     * Loads a language file in the current locale.
     *
     * @return array|void
     */
    protected function load(string $translateFile, string $locale)
    {
        $path = "{$locale}/{$translateFile}.php";
        $file = realpath($this->filesDirPath . $path);

        if (\is_file($file)) {
            $lang = include $file;
        } else {
            // llog("Lang file {$translateFile} not exists", 'translate');
        }

        $this->translates[$locale][$translateFile] = $lang ?? [];
    }

    // /**
    //  * Save a language file in the current locale.
    //  */
    // protected function save(string $translateFile, string $locale, string $line)
    // {


    //     $path = "{$locale}/{$translateFile}.php";
    //     $file = $this->filesDirPath . $path;

    //     $fileContent = "<?php\nreturn "
    //         . preg_replace(
    //             '/^array \((.*)\)$/isu',
    //             "[$1]",
    //             var_export($this->translates[$locale][$translateFile], true)
    //         )
    //         . ';';

    //     file_put_contents($file, $fileContent);

    //     llog([
    //         'locale' => $locale,
    //         'file' => $translateFile,
    //         'line' => $line,
    //     ], 'translate', 'autoadd');
    // }
}
