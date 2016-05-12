<?php
namespace Ttree\Flow\NatsIo;

/*
 * This file is part of the Ttree.Flow.NatsIo package.
 *
 * (c) Build with love by ttree agency - www.ttree.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Nats\Connection;
use Nats\ConnectionOptions;
use Packagist\Api\Result\Package;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Connection Factory
 *
 * @Flow\Scope("singleton")
 * @api
 */
class ConnectionFactory
{
    /**
     * @var array
     * @Flow\InjectConfiguration(path="presets")
     */
    protected $presets;

    /**
     * Connection Pool
     *
     * @var array
     */
    protected $pool = [];

    /**
     * @param string $preset
     * @return Connection
     * @throws Exception
     */
    public function create($preset = 'default')
    {
        if (isset($this->pool[$preset])) {
            return $this->pool[$preset];
        }
        if (trim($preset) === '') {
            throw new Exception(sprintf('Missing preset name', $preset), 1461667378);
        }
        if (!isset($this->presets[$preset])) {
            throw new Exception(sprintf('Invalid preset name (%s)', $preset), 1461667379);
        }
        $options = new ConnectionOptions();
        foreach ($this->presets[$preset] as $propertyName => $propertyValue) {
            ObjectAccess::setProperty($options, $propertyName, $propertyValue);
        }
        $connection = new Connection($options);
        $this->pool[$preset] = $connection;
        return $connection;
    }
}
