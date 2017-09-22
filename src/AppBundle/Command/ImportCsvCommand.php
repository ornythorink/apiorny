<?php

namespace AppBundle\Command;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Utils\Sources;
use Cocur\Slugify\Slugify;

class ImportCsvCommand extends ContainerAwareCommand
{

    protected $source;
    protected $locale;
    protected $filename;
    protected $prefix;
    protected $feed;
    protected $em;
    protected $repoFeeds;
    protected $blacklist;
    protected $whitelist;

    protected function configure()
    {
        $this
            ->setName('importcsv:run')
            ->setDescription('Import products from CSV file')
            ->addArgument(
                'source',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'locale',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'feedId',
                InputArgument::OPTIONAL
            )
            ->addArgument(
                'filename',
                InputArgument::OPTIONAL
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger = $this->getContainer()->get('logger');

        $this->locale = $input->getArgument('locale');
        $this->source = $input->getArgument('source');
        //$this->prefix = Sources::getSourceKey($this->source,'prefix');
        $this->em =  $this->getContainer()->get('doctrine')->getManager();
        $this->repoFeeds = $this->em->getRepository('AppBundle:Feedcsv');

        // si aucun feed à parcourir
        if($this->shouldReset()) {
            $this->reset();
        }

        // @todo transaction ?
        $this->feed = $this->repoFeeds->retrieveNextCsvFeed($this->source, $this->locale);
        $this->flag();


        $env = $this->getContainer()->get('kernel')->getEnvironment();

        $csvFile = $this->feed->getSiteslug()  .
            '-'. strtolower($this->source) . '-' . $env . ".csv";

        $this->setPathToStore($csvFile);
        // @todo faire webgain  et pas assez de privileges
        $this->copyFeed();

        $data = $this->getDataFromCsvExtractor();
        $method = $this->source;

        $this->loadBlacklist();
        $this->loadWhitelist();
        $this->$method($data);

        unlink($csvFile);
    }

    protected function getDataFromCsvExtractor()
    {
        /* @todo injecter du container */
        $converter = $this->getContainer()->get('import.csvtoarray');

        $sources = new Sources();
        $separator = $sources->getSeparator(strtoupper($this->source));

        /* @todo delimiter and option */
        $data = $converter->convert($this->getPathToStore(), $separator );
        return $data;
    }

    public function flag()
    {
        // dans tous les cas on flag
        $feedupdated = $this->repoFeeds->find($this->feed->getId());
        $feedupdated->setFlagbatched('Y');
        $this->em->persist($feedupdated);
        $this->em->flush();
        $this->em->clear();

    }

    public function shouldReset()
    {
        $flaggedActiveFeedsToProcess = $this->repoFeeds->getFeedsToProcess($this->source, $this->locale);
        if(count($flaggedActiveFeedsToProcess) == 0)
        {
            return true;
        }
        return false;
    }

    public function reset()
    {
        $this->repoFeeds->resetFeeds($this->locale);
    }

    protected function copyFeed()
    {
        try
        {
            $request = new \GuzzleHttp\Client();
            $response = $request->get(trim($this->feed->getFeed() ));
            $response = $response->getBody()->getContents();

            $fp = fopen($this->getPathToStore(), "wb");
            fwrite($fp, $response);
            fclose($fp);

            return true;
        }
        catch (\Exception $e)
        {
            // @todo  un vrai log et une action
            var_dump($e->getMessage());
            // Log the error or something
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getPathToStore()
    {
        return $this->pathToStore;
    }

    /**
     * @param mixed $pathToStore
     */
    public function setPathToStore($pathToStore)
    {
        $this->pathToStore = $pathToStore;
    }

    public function loadBlacklist()
    {
        $blacklistRepo = $this->em->getRepository('AppBundle:BlacklistCategories');
        $terms = $blacklistRepo->loadBlacklist($this->locale);
        foreach($terms as $term)
        {
            $this->blacklist[$term->getName()] = 1;
        }
    }

    public function loadWhitelist()
    {
        $whitelistRepo = $this->em->getRepository('AppBundle:WhitelistCategories');
        $terms = $whitelistRepo->loadWhitelist($this->locale);
        foreach($terms as $term)
        {
            $this->whitelist[$term->getName()] = 1;
        }
    }

    public function insertPending($categoryName)
    {
        if(
            !isset(
                $this->blacklist[$categoryName]
            )
            &&
            !isset(
                $this->whitelist[$categoryName]
            )
        ) {

            //$pendingRepo = $this->em->getRepository('AppBundle:Pending');
            $rawquery = <<<SQL
        INSERT INTO `shoes`.`pending`
                ( `id`, `createdAt`, `label`  )
                VALUES  (
                 :id, :createdat, :label
                )
                ON DUPLICATE KEY UPDATE updateAt = :now;
SQL;
            //slugify id
            $now = new \DateTime('now');
            $slugify = new Slugify();

            $statement = $this->em->getConnection()->prepare($rawquery);
            $statement->bindValue('id', $slugify->slugify($categoryName));
            $statement->bindValue('createdat', $now->format('Y-m-d H:i:s'));
            $statement->bindValue('label', $categoryName);
            $statement->bindValue('now', $now->format('Y-m-d H:i:s'));
        }
    }

    public function znx($data)
    {

        $rawquery = <<<SQL
        INSERT INTO `shoes`.`products`
                (  `name`, `price`, `promo`,`url`, `short_url`, `currency`,`logostore`,
                 `program`, `status`,  `brand`, `image`, `source_id`, `source_type`,  `actif`, `locale`,
                  `category_merchant`,  `createdAt`, `updateAt`, `description`, `ean` )
                VALUES  (
                 :name,  :price, :promo ,:url, :short_url, :currency, :logostore,
                :program , :status, :brand ,:image, :source_id, :source_type, :actif,
                :locale, :category_merchant, :createdAt, :updateAt, :description,
                :ean
                )
                ON DUPLICATE KEY UPDATE updateAt = :now;
SQL;
        $statement = $this->em->getConnection()->prepare($rawquery);
        $now = new \DateTime('now');
        foreach($data as $row)
        {
            if(isset( $this->blacklist[$row['MerchantProductCategoryPath']] )) {
                // @todo increment hit
                break;
            } else { // si la categorie n'est pas dans la blacklist
                $statement->bindValue('name', $row['ProductName']	);
                $statement->bindValue('price', $row['ProductPrice']);
                if ($row['ProductPriceOld'] == "")
                {
                    $row['ProductPriceOld'] = 0.00;
                }
                $statement->bindValue('promo', $row['ProductPriceOld']);
                $statement->bindValue('url', $row['ZanoxProductLink'] );
                $statement->bindValue('short_url', MD5($row['ZanoxProductLink']) );
                $statement->bindValue('currency',  $row['CurrencySymbolOfPrice']);
                $statement->bindValue('logostore', null);
                $statement->bindValue('program', $row['ProductManufacturerBrand'] );
                $statement->bindValue('status', "Validation" );
                $statement->bindValue('brand', $row['ProductManufacturerBrand'] );
                $statement->bindValue('image', $row['ImageSmallURL']);
                $statement->bindValue('source_id', 'ZNX' );
                $statement->bindValue('source_type', 'CSV');
                $statement->bindValue('actif', 'Y');
                $statement->bindValue('locale', $this->locale);
                $statement->bindValue('category_merchant', $row['MerchantProductCategoryPath'] );
                $statement->bindValue('createdAt', $now->format('Y-m-d H:i:s'));
                $statement->bindValue('updateAt', $now->format('Y-m-d H:i:s'));
                $statement->bindValue('description', $row['ProductShortDescription'] );
                $statement->bindValue('ean', $row['ProductEAN']  );
                $statement->bindValue('now', $now->format('Y-m-d H:i:s'));
                $statement->execute();
            }
        }

    }

    public function tdd($data)
    {
        $rawquery = <<<SQL
            INSERT INTO `shoes`.`products`
                    (  `name`, `price`, `promo`,`url`, `short_url`, `currency`,`logostore`,
                     `program`, `status`,  `brand`, `image`, `source_id`, `source_type`,  `actif`, `locale`,
                      `category_merchant`,  `createdAt`, `updateAt`, `description`, `ean` )
                    VALUES  (
                     :name,  :price, :promo ,:url, :short_url, :currency, :logostore,
                    :program , :status, :brand ,:image, :source_id, :source_type, :actif,
                    :locale, :category_merchant, :createdAt, :updateAt, :description,
                    :ean
                    )
                    ON DUPLICATE KEY UPDATE updateAt = :now;
SQL;
        $statement = $this->em->getConnection()->prepare($rawquery);
        $now = new \DateTime('now');
            foreach($data as $row)
            {
                if(isset( $this->blacklist[$row['merchantCategoryName']] ))
                {
                    // @todo increment hit sur la blackliste
                } else {  // si la categorie n'est pas dans la blacklist

                    if ($row['previousPrice'] == "")
                    {
                        $row['previousPrice'] = 0.00;
                    }

                    $statement->bindValue('name', $row['name']	);
                    $statement->bindValue('price', $row['price']);
                    $statement->bindValue('promo', $row['previousPrice']);
                    $statement->bindValue('url', $row['productUrl'] );
                    $statement->bindValue('short_url', MD5($row['productUrl']) );
                    $statement->bindValue('currency',  $row['currency']);
                    $statement->bindValue('logostore', $row['programLogoPath']);
                    $statement->bindValue('program', $row['programName'] );
                    $statement->bindValue('status', "Validation" );
                    $statement->bindValue('brand', $row['brand'] );
                    $statement->bindValue('image', $row['imageUrl']);
                    $statement->bindValue('source_id', 'TDD' );
                    $statement->bindValue('source_type', 'CSV');
                    $statement->bindValue('actif', 'Y');
                    $statement->bindValue('locale', $this->locale);
                    $statement->bindValue('category_merchant', $row['merchantCategoryName'] );
                    $statement->bindValue('createdAt', $now->format('Y-m-d H:i:s'));
                    $statement->bindValue('updateAt', $now->format('Y-m-d H:i:s'));
                    $statement->bindValue('description', $row['description'] );
                    $statement->bindValue('ean', $row['ean']  );
                    $statement->bindValue('now', $now->format('Y-m-d H:i:s'));
                    $statement->execute();
                    }
            }

        }
}