<?php

namespace AppBundle\Command;


use AppBundle\Utils\TddDataSourceApi;
use AppBundle\Utils\TddFluxApiConverter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Validator\Constraints\DateTime;


class ImportTddApiCommand extends ContainerAwareCommand
{
    protected static $entityManager;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->importdddapi($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

    }


    protected function importdddapi(InputInterface $input, OutputInterface $output)
    {
        $locale = $input->getArgument('locale');

        self::$entityManager = $this->getContainer()->get('doctrine')->getManager();

        self::$entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $termsToRequest = $this->getTermsToRequest($locale);
        $api = new TddDataSourceApi();

        $rawquery = <<<SQL
        INSERT INTO `shoes`.`products`
                ( `id_api`,  `name`, `price`, `promo`,`url`,`currency`,`logostore`,
                 `program`, `status`,  `brand`, `image`, `source_id`, `source_type`,  `actif`, `locale`,
                  `category_merchant`,  `createdAt`, `updateAt`, `description`, `ean`)
                VALUES  (
                :id_api ,  :name,  :price, :promo ,:url, :currency, :logostore,
                :program , :status, :brand ,:image, :source_id, :source_type, :actif,
                :locale, :category_merchant, :createdAt, :updateAt, :description,
                :ean
                )
                ON DUPLICATE KEY UPDATE updateAt = :now;
SQL;
        $statement = self::$entityManager->getConnection()->prepare($rawquery);

        foreach($termsToRequest as $term)
        {
            for($i = 1; $i <= 2; $i++)
            {
                sleep(2);
                $flux = $api->getProductFlux($term, $locale, "127.0.0.1", "firefox", $i,  800);

                $converter = new TddFluxApiConverter();
                $converter->setFlux($flux);
                $converter->convertFlux();
                $converted = $converter->getItemsArray();

                $now = new \DateTime();
                foreach($converted as $item)
                {
                    $statement->bindValue('id_api', $item['apiid'] );
                    $statement->bindValue('name', $item['name']	);
                    $statement->bindValue('price', $item['price']);
                    $statement->bindValue('promo', $item['oldPrice']);
                    $statement->bindValue('url', $item['url'] );
                    $statement->bindValue('currency',  $item['currency']);
                    $statement->bindValue('logostore', $item['logostore']);
                    $statement->bindValue('program', $item['program'] );
                    $statement->bindValue('status', $item['status'] );
                    $statement->bindValue('brand', $item['brand'] );
                    $statement->bindValue('image', $item['image']);
                    $statement->bindValue('source_id', $item['sourceId']);
                    $statement->bindValue('source_type', $item['source_type'] );
                    $statement->bindValue('actif', 'Y');
                    $statement->bindValue('locale', $locale);
                    $statement->bindValue('category_merchant', $item['merchantCategory'] );
                    $statement->bindValue('createdAt', $now->format('Y-m-d H:i:s'));
                    $statement->bindValue('updateAt', $now->format('Y-m-d H:i:s'));
                    $statement->bindValue('description', $item['shortDescription'] );
                    $statement->bindValue('ean', 1);
                    $statement->bindValue('now', $now->format('Y-m-d H:i:s'));
                    $statement->execute();
                }
                $i++;
            }
        }
    }

    protected function getTermsToRequest($locale)
    {
        return self::$entityManager->getRepository('AppBundle:Categories')->findBy(
            array(
                'actif'  => 1,
                'locale' => $locale
            )
        );
    }


    protected function configure()
    {

        // Name and description for app/console command
        $this
            ->setName('importdddapi:run')
            ->setDescription('Import products from TDD api')
            ->addArgument(
                'locale',
                InputArgument::REQUIRED
            );
    }
}