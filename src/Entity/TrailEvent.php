<?php
namespace src\Entity;

use src\Constant\ConstantConstant;

class TrailEvent extends Event
{
    protected string $onPlayerName;

    public function __construct(string $playerName)
    {
        $this->type = '';
        $this->quantity = 1;

        // Joueur sur qui est prise l'aspiration
        $this->onPlayerName = $playerName ?? '';
    }

    public function __toString(): string
    {
        $str = parent::__toString();
        return $str . ConstantConstant::CST_TAB.'sur le joueur : '.$this->onPlayerName.ConstantConstant::CST_EOL;
    }
}
