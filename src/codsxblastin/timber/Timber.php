<?php

namespace codsxblastin\timber;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;

class Timber extends PluginBase implements Listener {

    /** @var array $validAxeTypes */
    public array $validAxeTypes = [item::GOLDEN_AXE, item::IRON_AXE, item::DIAMOND_AXE];

    /** @var array $validLogTypes */
    public array $validLogTypes = [block::WOOD, block::WOOD2];

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function init(Player $player, BlockBreakEvent $event): void
    {
        $itemHand = $player->getInventory()->getItemInHand();
        if ($event instanceof BlockBreakEvent) {
            $block = $event->getBlock();
            if ($player->isSneaking()) {
                $validAxeTypes = $this->validAxeTypes;
                if ($validAxeTypes == $itemHand) {
                    $validLogTypes = $this->validLogTypes;
                    if ($block->getId() == is_array($validLogTypes)) {
                        $this->startTimber($block, $player);
                    }
                }
            }
        }
    }

    public function startTimber(Block $block, Player $player, int $mined = 0): void
    {
        $itemHand = $player->getInventory()->getItemInHand();
        for ($i = 0; $i <= 5; $i++) {
            if ($mined > 800) {
                break;
            }
                $side = $block->getSide($i);
                if ($block->getId() !== Block::WOOD && $side->getId() !== Block::WOOD2) {
                    continue;
                }
            $player->getLevel()->useBreakOn($side, $itemHand, $player);
            $mined++;
            $this->startTimber($side, $player, $mined);
        }
    }

}
