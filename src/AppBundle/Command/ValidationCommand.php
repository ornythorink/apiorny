<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use  AppBundle\Utils\Config;


class ValidationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('validation:run')
            ->setDescription('Valide les produits en whitelist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $batchSize = 20;
        $i = 0;
        $q = $em->createQuery(<<<TAG
                              SELECT p
                              FROM AppBundle:Products p
                              INNER JOIN  AppBundle:WhitelistCategories as w
                              WITH p.categoryMerchant = w.name
                              WHERE  p.status = 'Validation'

TAG
        )->setMaxResults(1000);

        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $products = $row[0];
            $products->setStatus("Ok");
            $em->persist($products);
            if (($i % $batchSize) === 0) {
                $em->flush(); // Executes all updates.
                $em->clear(); // Detaches all objects from Doctrine!
            }
            ++$i;
        }
        $em->flush();
        $em->clear();
    }
}
