<?php

declare(strict_types=1);

namespace Howto\Console\Command\FakeData;

use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateCommand extends Command
{
    public static $defaultName = 'fake-data:generate';

    protected function configure()
    {
        $this->setDescription('Build fake data');

        $this->addArgument('type', InputArgument::REQUIRED);
        $this->addArgument('size', InputArgument::REQUIRED);
        $this->addArgument('format', InputArgument::OPTIONAL, 'Format of the output (only CSV is supported).', 'csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $size = $input->getArgument('size');
        $format = $input->getArgument('format');

        $faker = Factory::create('fr_FR');

        switch ($type) {
            case 'product':
            case 'products':
                for ($i = 0; $i < $size; $i++) {
                    fputcsv(
                        STDOUT,
                        [
                            'sku' => $faker->regexify('[A-Z0-9]{3}-[A-Z0-9]{10}-[A-Z0-9]{3}'),
                            'name' => $faker->sentence(6),
                            'slug' => $faker->slug(),
                            'shortDescription' => $faker->paragraph(),
                            'description' => $faker->paragraphs(4, true),
                            'price' => number_format($faker->numberBetween(1, 200000) / 100, 4),
                            'image_1-url' => $faker->imageUrl(),
                            'image_1-code' => $faker->uuid,
                            'image_1-name' => $faker->sentence(6),
                            'image_1-slug' => $faker->slug(),
                            'image_2-url' => $faker->imageUrl(),
                            'image_2-code' => $faker->uuid,
                            'image_2-name' => $faker->sentence(6),
                            'image_2-slug' => $faker->slug(),
                            'image_3-url' => $faker->imageUrl(),
                            'image_3-code' => $faker->uuid,
                            'image_3-name' => $faker->sentence(6),
                            'image_3-slug' => $faker->slug(),
                            'image_4-url' => $faker->imageUrl(),
                            'image_4-code' => $faker->uuid,
                            'image_4-name' => $faker->sentence(6),
                            'image_4-slug' => $faker->slug(),
                        ]
                    );
                }
                break;

            case 'customer':
            case 'customers':
            case 'user':
            case 'users':
            case 'person':
            case 'persons':
                for ($i = 0; $i < $size; $i++) {
                    fputcsv(
                        STDOUT,
                        [
                            'firstName' => $faker->firstName,
                            'lastName' => $faker->lastName,
                            'email' => $faker->safeEmail,
                            'address' => $faker->address,
                            'postcode' => $faker->postcode,
                            'city' => $faker->city,
                        ]
                    );
                }
                break;
        }

        return 0;
    }
}