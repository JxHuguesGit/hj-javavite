<?php
namespace src\Entity;

use src\Constant\ConstantConstant;
use src\Constant\PatternConstant;

class LogFile extends Entity
{
    private array $lines;
    private array $tab;
    private bool $blnPitStop;
    private bool $blnTrail;
    private int $dnfPosition;
    private int $cptStartPosition;
    private int $cptEndPosition;
    private Event $tempEvent;
    private Game $objGame;
    private bool $ignoreNextMove;

    public function __construct(string $fileName=null)
    {
        if ($fileName!=null) {
            $handle = fopen(PLUGIN_PATH.$fileName, 'r');
            if ($handle!==false) {
                while (!feof($handle)) {
                    $line = fgets($handle);
                    $strEncoded = mb_detect_encoding($line);
                    if ($strEncoded!='UTF-8' && $strEncoded!='') {
                        $this->lines[] = mb_convert_encoding($line, 'ISO-8859-1', $strEncoded);
                    } else {
                        $this->lines[] = $line;
                    }
                }
                fclose($handle);
            }
        }
        $this->tab = [
            'players' => [],
        ];
        $this->blnPitStop = false;
        $this->blnTrail = false;
        $this->ignoreNextMove = false;
        $this->initGame();
    }

    private function initGame(): void
    {
        $this->objGame = new Game();
    }

    private function isLineAnEvent(string $line): bool
    {
        $bln = true;
        if (preg_match(PatternConstant::FR_PATTERN_DNF, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new DnfEvent([$this->dnfPosition]));
            $this->dnfPosition--;
        } elseif (preg_match(PatternConstant::FR_PATTERN_DNF2, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new DnfEvent([$this->dnfPosition, ConstantConstant::CST_TIRE]));
            $this->dnfPosition--;
        } elseif (preg_match(PatternConstant::FR_PATTERN_PNEUS, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new TireEvent([ConstantConstant::CST_TIRE, $matches[2], $matches[3]]));
        } elseif (preg_match(PatternConstant::FR_PATTERN_BLOCKED, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new DnfEvent([$this->dnfPosition, ConstantConstant::CST_BLOCKED, $matches[2]]));
            $this->dnfPosition--;
        } elseif (preg_match(PatternConstant::FR_PATTERN_CONSO, $line, $matches)
            || preg_match(PatternConstant::FR_PATTERN_CONSO2, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new FuelEvent([$matches[2]]));
        } elseif (preg_match(PatternConstant::FR_PATTERN_FREIN, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new BrakeEvent([ConstantConstant::CST_BRAKE, 1]));
            $this->ignoreNextMove = true;
        } elseif (preg_match(PatternConstant::FR_PATTERN_ASPI, $line, $matches)) {
            $this->tempEvent = new TrailEvent($matches[2]);
            $this->blnTrail = true;
        } elseif (preg_match(PatternConstant::FR_PATTERN_LATE_BRK, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new BrakeEvent([ConstantConstant::CST_TRAIL, 1]));
        } elseif (preg_match(PatternConstant::FR_PATTERN_TAQ, $line, $matches)) {
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new TaqEvent());
        } else {
            $bln = false;
        }
        return $bln;
    }

    public function isAnotherLine(string $line): bool
    {
        $bln = true;
        if (preg_match(PatternConstant::FR_PATTERN_MOVE, $line, $matches)) {
            if ($this->ignoreNextMove) {
                $this->ignoreNextMove = false;
                return $bln;
            }
            $currentPlayer = $this->objGame->getPlayerByPlayerName($matches[1]);
            if ($this->blnTrail) {
                $activePlayer = $this->objGame->getActivePlayer();
                if ($activePlayer->isEqual($currentPlayer)) {
                    $this->tempEvent->setType(ConstantConstant::CST_ACCEPTED);
                } else {
                    $this->tempEvent->setType(ConstantConstant::CST_DECLINED);
                }
                $this->objGame->addGameEvent($currentPlayer, $this->tempEvent);
                $this->blnTrail = false;
                $this->tempEvent = new Event();
            }
            $this->objGame->addGameEvent(
                $currentPlayer,
                new GearEvent([(int)substr($matches[2], 0, 1), $matches[3]]));
            $this->objGame->setActivePlayer($currentPlayer);
        } elseif (preg_match(PatternConstant::FR_PATTERN_WINNER, $line, $matches)
            || preg_match(PatternConstant::FR_PATTERN_FINISH, $line, $matches)) {
            $this->objGame->setFinalPosition(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                $this->cptEndPosition);
            $this->cptEndPosition++;
        } elseif ($this->blnPitStop || preg_match(PatternConstant::FR_PATTERN_PITSTOP, $line, $matches)) {
            $this->dealWithPitStop($line);
        } elseif (preg_match(PatternConstant::FR_PATTERN_BRK_CCL, $line, $matches)) {
            $this->ignoreNextMove = true;
        } else {
            $bln = false;
        }
        return $bln;
    }

    public function parse(): array
    {
        $this->cptStartPosition = 1;
        $this->dnfPosition = 0;
        $this->cptEndPosition = 1;
        $this->tempEvent = new Event();

        $arrLignesNonTraitees = [];
        
        foreach ($this->lines as $line) {
            if ($this->excludeLines($line)) {
                continue;
            }

            if ($this->isLineAnEvent($line)) {
                continue;
            }

            if ($this->isAnotherLine($line)) {
                continue;
            }

            if ($this->isLineATest($line)) {
                continue;
            }

            // Ligne non traitée.
            if ($line!='') {
                $arrLignesNonTraitees[] = $line;
            }
        }
        return $arrLignesNonTraitees;
    }

    private function isLineATest(string $line): bool
    {
        $bln = true;
        if (preg_match(PatternConstant::FR_PATTERN_TEST, $line, $matches)) {
            $this->objGame->addGameTest($matches);
            if ($matches[2]=='Départ') {
                $this->cptStartPosition++;
                $this->dnfPosition++;
            }
        } elseif (preg_match(PatternConstant::FR_PATTERN_METEO, $line, $matches)) {
            if ($matches[3]=='et') {
                $type = 'Variable';
            } elseif ($matches[3]=='<') {
                $type = 'Pluie';
            } else {
                // >
                $type = 'Beau temps';
            }
            $this->objGame->addGameTest([null, '', 'Météo', $matches[2], $type]);
        } else {
            $bln = false;
        }

        return $bln;
    }

    private function excludeLines(string $line): bool
    {
        $blnOk = false;
        // L'idée est de chercher certaines phrases clés qui sont à ignorer.
        // Ca permet d'éviter de passer dans toutes les regexp et de ne rien trouver.
        $checks = [
            "Bienvenue dans les essais",
            "La piste est m",
            "La piste est s",
            "Score = Nombre de Coups",
            "Essai n°",
            "de pénalité pour être sorti du virage",
            "Le commissaire de course",
            "En cas de comportement",
            "La course commencera dès le choix",
            "Faites vrombir les moteurs",
            "choisi son stand",
            "à vous de choisir votre stand",
            "c'est votre tour!",
            "est parti comme une fusée",
            "perd un morceau",
            "perd un point moteur",
            "perd en adhérence",

            "a calé !",
            "abandon par un accrochage",
            "automatiquement raté du fait de l'élimination",
            "Il va pleuvoir pendant toute la course.",
            "Le beau temps",
            "Le temps est au beau fixe",
            "Le temps est variable",
            "Le temps reste très incertain",
            "La pluie semble s'installer",
            "La pluie s'installe défintivement",
        ];
        foreach ($checks as $check) {
            if (strpos($line, $check)!==false) {
                return true;
            }
        }
        return $blnOk;
    }
    
    private function dealWithPitStop(string $line): void
    {
        if (preg_match(PatternConstant::FR_PATTERN_LONG_STOP, $line, $matches)) {
            // On ajoute un arrêt long.
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new PitStopTest(true));
            $this->blnPitStop = false;
        } elseif (preg_match(PatternConstant::FR_PATTERN_SHORT_FAIL, $line, $matches)) {
            // 13champion93 termine son tour à enguirlander les mécanos ! (jet = 17 , requis <10)
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new PitStopTest(false, $matches[2]));
                $this->blnPitStop = false;
        } elseif (preg_match(PatternConstant::FR_PATTERN_SHORT_SUCC, $line, $matches)) {
            // Antho repart immédiatement des stands ! (jet = 1 , requis <10)
            $this->objGame->addGameEvent(
                $this->objGame->getPlayerByPlayerName($matches[1]),
                new PitStopTest(false, $matches[2]));
                $this->objGame->setIgnoreMove();
            $this->blnPitStop = false;
        } else {
            $this->blnPitStop = true;
        }
    }
    
    public function display(): string
    {
        return $this->objGame->getController()->display();
    }
}
