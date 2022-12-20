<?php

namespace Bikelec\GoogleSheets\Helper;


use Google\Client as Google_Client;
use Google\Service\Sheets as Google_Service_Sheets;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\Handler\StreamHandler as MonologStreamHandler;
use Monolog\Logger;

class Data
{

    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');
        $log = new Logger('name');
        $log->pushHandler(new MonologStreamHandler('google-sheets.log', Logger::DEBUG));
        $client = new Google_Client();
        $client->setLogger($log);
        $client->useApplicationDefaultCredentials();
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setSubject('bikelec-admin@bikelec.es');

        return $client;
    }

    /**
     * Get Status
     */
    public function getStatus()
    {
        return $this->_scopeConfig->getValue('google_sheets/general/enabled');
    }

    /**
     * Get Account
     */
    public function getAccount()
    {
        return $this->_scopeConfig->getValue('google_sheets/general/account');
    }

    /**
     * Get Credentials
     */
    public function getCredentials()
    {
        return $this->_scopeConfig->getValue('google_sheets/general/credentials');
    }


}
