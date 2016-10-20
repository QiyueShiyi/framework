<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-02 19:17
 */
namespace Notadd\Foundation\Console\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
/**
 * Class Command
 * @package Notadd\Foundation\Console\Abstracts
 */
abstract class Command extends SymfonyCommand {
    /**
     * @var \Illuminate\Container\Container|\Notadd\Foundation\Application
     */
    protected $container;
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    /**
     * Command constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->container = $this->getContainer();
    }
    /**
     * @param $question
     * @param null $default
     * @return string
     */
    protected function ask($question, $default = null) {
        $question = new Question("<question>$question</question> ", $default);
        return $this->getHelperSet()->get('question')->ask($this->input, $this->output, $question);
    }
    /**
     * @param $command
     * @param array $arguments
     * @return int
     */
    public function call($command, array $arguments = []) {
        $instance = $this->getApplication()->find($command);
        $arguments['command'] = $command;
        return $instance->run(new ArrayInput($arguments), $this->output);
    }
    /**
     * @param $string
     */
    public function error($string) {
        $this->output->writeln("<error>$string</error>");
    }
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;
        return $this->fire();
    }
    /**
     * @return mixed
     */
    abstract protected function fire();
    /**
     * @return \Illuminate\Container\Container|\Notadd\Foundation\Application
     */
    protected function getContainer() {
        return Container::getInstance();
    }
    /**
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function getInput() {
        return $this->input;
    }
    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput() {
        return $this->output;
    }
    /**
     * @param $name
     * @return bool
     */
    protected function hasOption($name) {
        return $this->input->hasOption($name);
    }
    /**
     * @param $string
     */
    protected function info($string) {
        $this->output->writeln("<info>$string</info>");
    }
    /**
     * @param $question
     * @return string
     */
    protected function secret($question) {
        $question = new Question("<question>$question</question> ");
        $question->setHidden(true)->setHiddenFallback(true);
        return $this->getHelperSet()->get('question')->ask($this->input, $this->output, $question);
    }
    /**
     * @param $container
     */
    protected function setContainer($container) {
        $this->container = $container;
    }
    /**
     * @param array $headers
     * @param $rows
     * @param string $style
     */
    public function table(array $headers, $rows, $style = 'default') {
        $table = new Table($this->output);
        if($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }
        $table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
    }
}