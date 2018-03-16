<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class RemoveOldSdc extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('removeoldsdc:run')
            ->setDescription('Remove old sdc products');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $expirationDate = new \DateTime();
        $expirationDate->modify('-3 days');
        $exDate = $expirationDate->format('Y-m-d H:i:s');


        $batchSize = 20;
        $i = 0;
        $q = $em->createQuery('SELECT p
                              FROM AppBundle:Products p
                              WHERE  p.createdat <= ?1
                              AND p.sourceId = ?2 ')->setMaxResults(1000);

        $q->setParameter(1, $exDate);
        $q->setParameter(2, "SDC");

        $iterableResult = $q->iterate();
        foreach ($iterableResult as $row) {
            $products = $row[0];
            $em->remove($products);
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