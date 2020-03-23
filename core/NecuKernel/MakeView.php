<?php

namespace core\NecuKernel;

use core\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeView extends Command
{
    protected $commandName = 'make:view';
    protected $commandDescription = "Make a view";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Who do you want to greet?";

    protected $commandOptionName = "cap"; // should be specified like "app:greet John --cap"
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
        $exploadname = explode('.',$name);
        $mkdir = __DIR__ . '/../../views';
        foreach ($exploadname as $key => $one) {
            $mkdir = $mkdir . '/' . $one;
            if(!is_dir($mkdir) && $key < count($exploadname) - 1) {
                mkdir($mkdir);
            }
        }
        if ($name) {
            $text = "<?php
/**
 * @var \$View View
 * @var \$isDevice MobileDetect
 */

use core\Helper;
use core\View;
use Detection\MobileDetect;

?>
";
        } else {
            $output->writeln('INSERT A VIEW NAME');
        }

        if(file_exists($mkdir.'.php')) {
            $output->writeln('There is already a view with this name :(');
        } else {
            $output->writeln($mkdir.'.php');
            file_put_contents($mkdir.'.php',$text);
            $output->writeln('The view was successfully created!:)');
        }

    }
}