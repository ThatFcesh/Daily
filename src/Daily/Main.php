<?php

declare(strict_types=1);

namespace Daily;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
    /** @var Config */
    private $players;
    /** @var string */
    private $command;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->reloadConfig();
        $this->players = new Config($this->getDataFolder() . "players.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->command = $this->getConfig()->get("command");
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(!$this->players->exists($name)) {
            $this->players->set($name, date("m-d-Y"));
            $this->players->save();
            $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $player->getLanguage()), $this->command);
        } else {
            $date = $this->players->get($name);
            if(date("m-d-Y") !== $date) {
                $this->players->set($name, date("m-d-Y"));
                $this->players->save();
                $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $player->getLanguage()), $this->command);
            }
        }
    }
}
