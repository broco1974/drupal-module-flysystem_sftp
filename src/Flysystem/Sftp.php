<?php

/**
 * @file
 * Contains \Drupal\flysystem_sftp\Flysystem\Sftp.
 */

namespace Drupal\flysystem_sftp\Flysystem;

use Drupal\flysystem\Flysystem\Adapter\MissingAdapter;
use Drupal\flysystem\Flysystem\Ftp as FtpFlysystemPluginBase;
use League\Flysystem\Sftp\SftpAdapter;

/**
 * Drupal plugin for the "SFTP" Flysystem adapter.
 */
class Sftp extends FtpFlysystemPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getAdapter() {
    try {
      $adapter = new SftpAdapter($this->configuration);
      $adapter->connect();
    }

    catch (\Exception $e) {
      $adapter = new MissingAdapter();
    }

    return $adapter;
  }

  /**
   * {@inheritdoc}
   */
  public function ensure($force = FALSE) {
    if ($this->getAdapter() instanceof SftpAdapter) {
      return array();
    }

    return array(array(
      'severity' => WATCHDOG_ERROR,
      'message' => 'There was an error connecting to the SFTP server %host:%port.',
      'context' => array(
        '%host' => $this->configuration['host'],
        '%port' => isset($this->configuration['port']) ? $this->configuration['port'] : 22,
      ),
    ));
  }

}
