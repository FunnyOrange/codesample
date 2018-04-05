<?php


class Human{
    private $name;
    private $intellect;
    private $agility;
    private $stamina;
    private $strength;

    public function __construct($name,$intellect, $agility, $stamina, $strength)
    {
        $this->name = $name;
        $this->intellect = $intellect;
        $this->agility = $agility;
        $this->stamina = $stamina;
        $this->strength = $strength;
    }

    public function getName(){
        return $this->name;
    }
    public function getIntellect(){
        return $this->intellect;
    }
    public function getAgility(){
        return $this->agility;
    }
    public function getStamina(){
        return $this->stamina;
    }
    public function getStrength(){
        return $this->strength;
    }

}

class Female extends Human{
    private $haste;
    private $spirit;
    public function __construct($name, $intellect, $agility, $stamina, $strength, $haste, $spirit)
    {
        parent::__construct($name, $intellect, $agility, $stamina, $strength);
        $this->haste = $haste;
        $this->spirit = $spirit;
    }

    public function getHaste(){
        return $this->haste;
    }
    public function getSpirit(){
        return $this->spirit;
    }
}

class Male extends Human{
    private $versatility;
    public function __construct($name, $intellect, $agility, $stamina, $strength, $versatility)
    {
        parent::__construct($name, $intellect, $agility, $stamina, $strength);
    }

}

$newFemale = new Female('Sylvanas', 100, 80, 40, 33, 70, 20);
echo "Statistics of ".$newFemale->getName()." are: ";
echo "\n intellect: ".$newFemale->getIntellect();
echo "\n agility: ". $newFemale->getAgility();
echo "\n stamina: ". $newFemale->getStamina();
echo "\n strength: ". $newFemale->getStrength();
echo "\n haste: ". $newFemale->getHaste();
echo "\n spirit: ". $newFemale->getSpirit();

$newMale = new Male('Magni Bronzebeard', 88, 66, 100, 96, 77);
echo "\nStatistics of ".$newMale->getName()." are: ";
echo "\n intellect: ".$newMale->getIntellect();
echo "\n agility: ". $newMale->getAgility();
echo "\n stamina: ". $newMale->getStamina();
echo "\n strength: ". $newMale->getStrength();
echo "\n haste: ". $newFemale->getHaste();

