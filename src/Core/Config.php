<?php


namespace Core;


class Config
{
    const ENV_PARAM_NAME = 'ENV';
    const USE_DOT_ENV_PARAM = 'DOT_ENV';
    const DEV_ENV = 'dev';

    /**
     * Instance of config file
     *
     * @var Config $config
     */
    private static $config;

    /**
     * An array with config data
     *
     * @var array
     */
    private $configArray;

    private function __construct() {}

    /**
     * Get environment variable
     *
     * @return string
     */
    public static function getEnv(): string {
        return !!getenv(self::ENV_PARAM_NAME) ? getenv(self::ENV_PARAM_NAME) : self::DEV_ENV;
    }

    /**
     * Check if environment is dev
     *
     * @return bool
     */
    public static function isDevEnv(): bool {
        return self::getEnv() === self::DEV_ENV;
    }

    /**
     * Get instance of Config class
     *
     * @return Config
     */
    public static function getConfig(): Config
    {
        if (!self::$config) {
            self::init();
        }

        return self::$config;
    }

    /**
     * Get value of config variable
     *
     * @param string $name  Name of variable
     * @param null $default Default value if variable not isset
     * @return mixed|null
     */
    public static function getValue(string $name, $default = null) {
        $config = self::getConfig();

        return isset($config->configArray[$name]) ? $config->configArray[$name] : $default;
    }

    /**
     * Initialise config instance
     */
    private static function init() {
        self::$config = new Config();

        self::$config->configArray = self::isDevEnv() ?
            array_merge(self::$config->parseEnvFile(), getenv()) :
            getenv()
        ;
    }

    /**
     * Parse .env file if exists
     * .env file must be located in root of application. See .env.example
     * @return array
     */
    private function parseEnvFile(): array {
        $res = [];
        $envFile = fopen(__DIR__.'/../../.env', 'r');

        if ($envFile) {
            while ($buffer = stream_get_line($envFile, 4096, PHP_EOL)) {
                $parts = explode('=', $buffer);
                if (count($parts) == 2) {
                    $res[$parts[0]] = trim($parts[1]);
                }
            }
        }

        return $res;
    }
}