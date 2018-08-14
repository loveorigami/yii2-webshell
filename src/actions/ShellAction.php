<?php

namespace lo\wshell\actions;

use Symfony\Component\Process\Process;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\console\ExitCode;
use yii\web\Response;

/**
 * Class ShellAction
 *
 * @package lo\wshell\controllers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShellAction extends Action
{
    /**
     * @var \Symfony\Component\Process\Process the process instance
     */
    protected $process;

    /**
     * @var string the shell command to execute
     */
    public $command;

    public $yiiScript = '@root/yii';

    /**
     * @var \Closure method to handle the output in a custom way. It gets called each time there
     * is more output available. To make the content available to the frontend you have to echo it
     * immediately. The function should have the signature `function ($action, $output, $isError)`.
     * `$action` is a reference to the action class, `$output` is the content to output and `$isError`
     * is a boolean which is true when this was an error output.
     *
     * Make sure to always flush the output. You can use `flushOutput()`of the action to do so.
     */
    public $outputCallback;

    /**
     * @var int the timeout and therefore max runtime of the command (defaults to five minutes).
     */
    public $timeout = 300;

    /**
     * @var int|string the expected exit code of the command to signal a success
     */
    public $commandExitCodeSuccess = ExitCode::OK;

    /**
     * @inheritdoc
     */
    public function init()
    {
        //validate shell command
        if (empty($this->command)) {
            throw new InvalidConfigException(Yii::t('app', 'Missing shell-command'));
        }

        $cmd = Yii::getAlias($this->yiiScript) . ' ' . $this->command . ' 2>&1';

        //create process instance
        $this->process = new Process($cmd);
        $this->process->setTimeout($this->timeout);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        //run the process
        $this->process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > ' . PHP_EOL . $buffer;
            } else {
                echo 'OUT > ' . PHP_EOL . $buffer;
            }
        });

        //return $this->process->getOutput();
    }
}
