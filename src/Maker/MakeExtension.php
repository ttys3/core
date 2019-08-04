<?php

namespace Bolt\Maker;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeExtension extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:extension';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new Bolt Extension')
            ->addArgument(
                'extensionName',
                InputArgument::OPTIONAL,
                'Choose a name for your Extension (e.g. <fg=yellow>FooBar</>)'
            )
            ->addArgument(
                'projectName',
                InputArgument::OPTIONAL,
                'Choose a namespace for your Extension (e.g. <fg=yellow>MyProject</>)'
            )
            ->addOption('no-template', null, InputOption::VALUE_NONE, 'Use this option to disable template generation')
            ->setHelp(file_get_contents(__DIR__.'/MakeExtension.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $extensionClassNameDetails = $generator->createClassNameDetails(
            '\\Extension\\' . $input->getArgument('projectName') . $input->getArgument('extensionName'),
            $input->getArgument('projectName') . '\\Qux',
            ''
        );

        $noTemplate = $input->getOption('no-template');
        $templateName = Str::asFilePath($extensionClassNameDetails->getRelativeNameWithoutSuffix()).'/index.html.twig';
//        dump($extensionClassNameDetails->getFullName());
//        dump($templateName);


//        $extensionPath = $generator->generateController(
//            $extensionClassNameDetails->getFullName(),
//            'controller/Controller.tpl.php',
//            [
//                'route_path' => Str::asRoutePath($extensionClassNameDetails->getRelativeNameWithoutSuffix()),
//                'route_name' => Str::asRouteName($extensionClassNameDetails->getRelativeNameWithoutSuffix()),
//                'with_template' => $this->isTwigInstalled() && !$noTemplate,
//                'template_name' => $templateName,
//            ]
//        );
//
//        if ($this->isTwigInstalled() && !$noTemplate) {
//            $generator->generateTemplate(
//                $templateName,
//                'controller/twig_template.tpl.php',
//                [
//                    'controller_path' => $extensionPath,
//                    'root_directory' => $generator->getRootDirectory(),
//                    'class_name' => $extensionClassNameDetails->getShortName(),
//                ]
//            );
//        }
//
//        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new controller class and add some pages!');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            // we only need doctrine/annotations, which contains
            // the recipe that loads annotation routes
            Annotation::class,
            'annotations'
        );
    }

    private function isTwigInstalled()
    {
        return class_exists(TwigBundle::class);
    }
}
