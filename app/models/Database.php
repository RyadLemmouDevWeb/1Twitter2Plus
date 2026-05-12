<?php

class Database
{
    private static $connection = null;
    private static $envLoaded = false;

    public static function getConnection()
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        self::loadEnvFiles();

        $name = self::getEnvValue('DB_NAME', 'twitter');
        $charset = self::getEnvValue('DB_CHARSET', 'utf8mb4');

        $configuredHost = self::getEnvValue('DB_HOST', null);
        $hosts = [];
        if ($configuredHost !== null && $configuredHost !== '') {
            $hosts[] = $configuredHost;
        } else {
            $hosts = ['localhost', '127.0.0.1'];
        }

        $configuredPort = self::getEnvValue('DB_PORT', null);
        $credentials = self::buildCredentialCandidates();
        $lastError = null;

        foreach ($hosts as $host) {
            $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
            if ($configuredPort !== null && $configuredPort !== '') {
                $dsn = "mysql:host={$host};port={$configuredPort};dbname={$name};charset={$charset}";
            }

            foreach ($credentials as $candidate) {
                list($username, $password) = $candidate;

                try {
                    self::$connection = new PDO($dsn, $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);

                    return self::$connection;
                } catch (PDOException $exception) {
                    $lastError = $exception;
                }
            }
        }

        $message = 'Database connection failed. Configure DB_HOST, DB_PORT, DB_NAME, DB_USER and DB_PASSWORD in .env (or .env.local).';
        if ($lastError instanceof Throwable) {
            $message .= ' Last error: ' . $lastError->getMessage();
        }

        throw new RuntimeException($message);
    }

    private static function buildCredentialCandidates()
    {
        $configuredUser = self::getEnvValue('DB_USER', null);
        $configuredPassword = self::getEnvValue('DB_PASSWORD', null);

        if ($configuredUser !== null && $configuredUser !== '') {
            return [
                [$configuredUser, $configuredPassword !== null ? $configuredPassword : ''],
            ];
        }

        return [
            ['twitter', 'root'],
            ['twitter', ''],
            ['root', 'root'],
            ['root', ''],
        ];
    }

    private static function loadEnvFiles()
    {
        if (self::$envLoaded) {
            return;
        }

        self::$envLoaded = true;

        $projectRoot = dirname(__DIR__, 2);
        $envFiles = [
            $projectRoot . '/.env',
            $projectRoot . '/.env.local',
        ];

        foreach ($envFiles as $envFilePath) {
            if (!is_readable($envFilePath)) {
                continue;
            }

            $lines = file($envFilePath, FILE_IGNORE_NEW_LINES);
            if ($lines === false) {
                continue;
            }

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') {
                    continue;
                }

                if (strpos($line, 'export ') === 0) {
                    $line = trim(substr($line, 7));
                }

                $separatorPos = strpos($line, '=');
                if ($separatorPos === false) {
                    continue;
                }

                $key = trim(substr($line, 0, $separatorPos));
                $value = trim(substr($line, $separatorPos + 1));
                if ($key === '') {
                    continue;
                }

                if (
                    (strlen($value) >= 2)
                    && (($value[0] === '"' && substr($value, -1) === '"')
                        || ($value[0] === "'" && substr($value, -1) === "'"))
                ) {
                    $value = substr($value, 1, -1);
                }

                if (getenv($key) !== false) {
                    continue;
                }

                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    private static function getEnvValue($envKey, $default)
    {
        $envValue = getenv($envKey);
        if ($envValue !== false) {
            return $envValue;
        }

        return $default;
    }
}