<?php

namespace Metadrop;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event;

class MetadropPlugin implements PluginInterface, EventSubscriberInterface, Capable {

  /**
   * The Input/Output helper interface.
   *
   * @var \Composer\IO\IOInterface
   */
  private $io;

  /**
   * The Composer Scaffold handler.
   *
   * @var \Metadrop\Handler
   */
  protected $handler;

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $this->io = $io;
    $this->handler = new Handler($composer, $io);
  }

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return ['Composer\Plugin\Capability\CommandProvider' => 'Metadrop\CommandProvider'];
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ScriptEvents::POST_UPDATE_CMD => 'scripthorInstaller',
      ScriptEvents::POST_INSTALL_CMD => 'scripthorInstaller',
    ];
  }

  /**
   * Post command event callback.
   *
   * @param \Composer\Script\Event $event
   *   The Composer event.
   *
   * @throws \Exception
   */
  public function scripthorInstaller(Event $event) {
    $this->io->write('scripthorInstaller: ' . $event->getName());
    $this->handler->createSymlinks();
  }

}
