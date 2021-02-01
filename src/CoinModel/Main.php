<?php

declare(strict_types = 1);
 
namespace CoinModel;

  use CoinModel\Model\CoinModel;
  use CoinModel\Utils\Utils;
  
  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;

  use pocketmine\Player;
  use pocketmine\math\Vector3;
  use pocketmine\entity\{Skin, Entity};
  use pocketmine\event\player\PlayerJoinEvent;
  use pocketmine\nbt\tag\{IntTag, StringTag, CompoundTag, ByteArrayTag};
 
	class Main extends PluginBase implements Listener 
	{
		public function onEnable(): void
	    {
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
			
			Entity::registerEntity(CoinModel::class, true); 
		}
		
	    public function onPlayerJoin(PlayerJoinEvent $event): void
	    {
			$player = $event->getPlayer(); 

			$texture = '/resources/model-texture.png'; 
			$geometry = '/resources/model-geometry.json'; 
			$id = 'geometry.coin-model'; 

			$this->spawnEntity($player, $texture, $geometry, $id); 
		}
		
		public function spawnEntity(Player $player, $texture, $geometry, $id): void
		{
			$texture = $this->getFile() . $texture;
			$geometry = file_get_contents($this->getFile() . $geometry);

			$skin = Utils::getSkinFromFile($texture);
			$nbt = Entity::createBaseNBT(new Vector3(100, 100, 100)); 

			$nbt->setTag(new CompoundTag("Skin", [
                new StringTag("Data", $skin->getSkinData()),
                new StringTag("Name", "name"),
                new StringTag("GeometryName", $id),
                new ByteArrayTag("GeometryData", $geometry)
			]));
            $nbt->setTag(new IntTag("position"));
			$nbt->setTag(new StringTag("player", "0"));
			
			$npc = new CoinModel($player->getLevel(), $nbt);
			$npc->spawnToAll();
		}
	}

?>
