<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace VendorFPF\Monolog\Handler;

use VendorFPF\Aws\Sdk;
use VendorFPF\Aws\DynamoDb\DynamoDbClient;
use VendorFPF\Aws\DynamoDb\Marshaler;
use VendorFPF\Monolog\Formatter\ScalarFormatter;
use VendorFPF\Monolog\Logger;
/**
 * Amazon DynamoDB handler (http://aws.amazon.com/dynamodb/)
 *
 * @link https://github.com/aws/aws-sdk-php/
 * @author Andrew Lawson <adlawson@gmail.com>
 */
class DynamoDbHandler extends \VendorFPF\Monolog\Handler\AbstractProcessingHandler
{
    const DATE_FORMAT = 'Y-m-d\\TH:i:s.uO';
    /**
     * @var DynamoDbClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $table;
    /**
     * @var int
     */
    protected $version;
    /**
     * @var Marshaler
     */
    protected $marshaler;
    /**
     * @param DynamoDbClient $client
     * @param string         $table
     * @param int            $level
     * @param bool           $bubble
     */
    public function __construct(\VendorFPF\Aws\DynamoDb\DynamoDbClient $client, $table, $level = \VendorFPF\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (\defined('Aws\\Sdk::VERSION') && \version_compare(\VendorFPF\Aws\Sdk::VERSION, '3.0', '>=')) {
            $this->version = 3;
            $this->marshaler = new \VendorFPF\Aws\DynamoDb\Marshaler();
        } else {
            $this->version = 2;
        }
        $this->client = $client;
        $this->table = $table;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $filtered = $this->filterEmptyFields($record['formatted']);
        if ($this->version === 3) {
            $formatted = $this->marshaler->marshalItem($filtered);
        } else {
            $formatted = $this->client->formatAttributes($filtered);
        }
        $this->client->putItem(array('TableName' => $this->table, 'Item' => $formatted));
    }
    /**
     * @param  array $record
     * @return array
     */
    protected function filterEmptyFields(array $record)
    {
        return \array_filter($record, function ($value) {
            return !empty($value) || \false === $value || 0 === $value;
        });
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new \VendorFPF\Monolog\Formatter\ScalarFormatter(self::DATE_FORMAT);
    }
}