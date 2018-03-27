<?php

namespace App\Authorization\Console;

use FOS\OAuthServerBundle\Entity\ClientManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
class ClientCreateCommand extends ContainerAwareCommand

{

    /**
     * @var ClientManager
     */
    private $clientManager;

    protected function configure ()
    {

        $this
            ->setName('oauth:client:create')
            ->setDescription('Creates a new client')
            ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_OPTIONAL, 'Sets the redirect uri. Use multiple times to set multiple uris.', [])
            ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_OPTIONAL, 'Set allowed grant type. Use multiple times to set multiple grant types', 'client_credentials')
        ;
    }
    protected function execute (InputInterface $input, OutputInterface $output)
    {

        $this->clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');;
        $client = $this->clientManager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes([$input->getOption('grant-type')]);
        $this->clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new client with  public id <info>%s</info>.', $client->getPublicId()));
    }
}