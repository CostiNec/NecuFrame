<?php

namespace core\NecuKernel;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StartServer extends Command
{
    protected $commandName = 'serve';
    protected $commandDescription = "Make a model";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Who do you want to greet?";

    protected $commandOptionName = "port"; // should be specified like "app:greet John --cap"
    protected $commandOptionDescription = 'If set, it will greet in uppercase letters';

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::OPTIONAL,
                $this->commandArgumentDescription
            )
            ->addOption(
                $this->commandOptionName,
                null,
                InputOption::VALUE_NONE,
                $this->commandOptionDescription
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);
        if($name == null) {
            $port= '8000';
            file_put_contents(__DIR__ . '../../script.sh', 'php -S localhost:' . $port . ' -t ' . __DIR__ . '/public');

        } else {
            $name = explode('=',$name);
            if(count($name) != 2) {
                $output->writeln('Your mean "php necu serve port=YOUR_PORT"???');
                die();
            }
            if(!is_numeric($name[1])) {
                $output->writeln('The port should be numerical, example "php necu serve port=8008"');
                die();
            }
            if(strtolower($name[0]) != 'port') {
                $output->writeln('You mean port not ' . $name[0]);
                die();
            }
            $port = $name[1];
            $text = 'php -S localhost:' . $port . ' -t ' . __DIR__ . '/../../public';
            $path =  __DIR__ . '/../../script.sh';
            exec('chmod 777 '.$path);
            $output->writeln(__DIR__ . '/../../script.sh');
            file_put_contents(__DIR__ . '/../../script.sh', $text);
            exec($path);
        }
    }
}