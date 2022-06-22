<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'add-product',
    description: 'Add product'
)]
class AddProductCommand extends Command
{
    private SymfonyStyle $io;
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The product name')
            ->addArgument('price', InputArgument::REQUIRED, 'The plain decimal for product price')
            ->addArgument('description', InputArgument::OPTIONAL, 'The plain text for product description')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('name')) {
            return;
        }

        $this->io->title('Add Product Command');
        $this->io->text([
            'short useage example:',
            ' $ php bin/console app:add-product name description price',
        ]);

        $name = $input->getArgument('name');
        if (null !== $name) {
            $this->io->text(' > <info>Product Name</info>: '.$name);
        } else {
            $name = $this->io->ask('name', null);
            $input->setArgument('name', $name);
        }

        // Ask for the password if it's not defined
        $description = $input->getArgument('description');
        if (null !== $description) {
            $this->io->text(' > <info>Product Description</info>: '. $description);
        } else {
            $description = $this->io->ask('Product Description');
            $input->setArgument('description', $description);
        }

        $price = $input->getArgument('price');
        if (null !== $price) {
            $this->io->text(' > <info>Product Price</info>: '.$price);
        } else {
            $price = $this->io->ask('price', null);
            $input->setArgument('price', $price);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-product-command');

        $name = $input->getArgument('name');
        $description = $input->getArgument('description');
        $price = $input->getArgument('price');


        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);


        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s',  'Product', $product->getName()));

        $event = $stopwatch->stop('add-product-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New product database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $product->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }
}
