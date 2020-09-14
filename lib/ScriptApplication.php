<?php

declare(strict_types=1);

/**
 * Application runner for test scripts
 *
 * @see https://magento.stackexchange.com/questions/39981/how-can-i-bootstrap-magento-2-in-a-test-php-script
 */
class ScriptApplication extends \Magento\Framework\App\Http implements \Magento\Framework\AppInterface
{
    private const SCRIPT_TEMPLATE = <<<'TEMPLATE'
<?php

declare(strict_types=1);

/**
 * @var $om \Magento\Framework\ObjectManagerInterface
 */

TEMPLATE;


    /**
     * {@inheritDoc}
     */
    public function launch()
    {
        $this->executeScript();

        return $this->_response;
    }

    /**
     * Executes the desired script in the desired scope
     *
     * @throws Exception
     */
    private function executeScript(): void
    {
        if ($this->isHelp()) {
            $this->getHelp();
            return;
        }

        if ($this->isCreate()) {
            $createName = $this->getCreateName();
            $this->createScript($createName);
            return;
        }

        $scriptName = $this->getScriptPath();
        if (!file_exists($scriptName)) {
            fwrite(STDERR, sprintf("script %s does not exist\n", $scriptName));
            $this->getHelp(true);
            return;
        }

        $run = function (\Magento\Framework\ObjectManagerInterface $om) use ($scriptName): void {
            $objectManger = $om;
            $di = $om;

            include $scriptName;
        };

        $ac = $this->getAreaCode();
        if ($ac === "") {
            $run($this->_objectManager);
            return;
        }

        /** @var \Magento\Framework\App\State $emulator */
        $emulator = $this->_objectManager->get(\Magento\Framework\App\State::class);
        $emulator->emulateAreaCode($ac, $run, [$this->_objectManager]);
    }

    /**
     * {@inheritDoc}
     */
    public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception): bool
    {
        return false;
    }

    /**
     * Determine if the user needs help
     *
     * @return bool
     */
    public function isHelp(): bool
    {
        $args = $_SERVER["argv"];
        $args = array_slice($args, 1);
        if (count($args) === 0) {
            return true;
        }

        foreach ($args as $arg) {
            if (in_array(strtolower($arg), ["-h", "--help", "help", "?"])) {
                return true;
            }
        }
        return false;

    }

    /**
     * Print help to the desired output
     *
     * @param bool $err
     */
    private function getHelp(bool $err = false): void
    {
        fwrite($err ? STDERR : STDOUT, sprintf("\nUsage: %s [options] filename [area-code]\n", $_SERVER["argv"][0]));
        fwrite($err ? STDERR : STDOUT, "\nOptions:\n");
        fwrite($err ? STDERR : STDOUT, "  -c, --create filename   Create a new script file\n");
        fwrite($err ? STDERR : STDOUT, "  -h, --help              Display this help text\n");
        fwrite($err ? STDERR : STDOUT, "\n");
    }

    /**
     * Infer the full script path
     *
     * @return string
     */
    private function getScriptPath(): string
    {
        $args = $_SERVER["argv"];
        if (!array_key_exists(1, $args)) {
            throw new LogicException("No script provided");
        }
        $sn = $args[1];
        return implode(DIRECTORY_SEPARATOR, [CUSTOM_SCRIPT_DIRECTORY, $sn]);
    }

    /**
     * Get the desired area code
     *
     * @return string
     */
    private function getAreaCode(): string
    {
        $args = $_SERVER["argv"];
        if (!array_key_exists(2, $args)) {
            return "";
        }
        return $args[2];
    }

    /**
     * @param string $name
     */
    private function createScript(string $name): void
    {
        $sp = implode(DIRECTORY_SEPARATOR, [CUSTOM_SCRIPT_DIRECTORY, $name]);
        file_put_contents($sp, self::SCRIPT_TEMPLATE);
    }

    private function isCreate(): bool
    {
        return $this->getCreateName() !== "";
    }

    private function getCreateName(): string
    {
        $opt = getopt("c:", ["create:"]);
        if (array_key_exists("c", $opt)) {
            return $opt["c"];
        }


        if (array_key_exists("create", $opt)) {
            return $opt["create"];
        }

        return "";
    }

}
