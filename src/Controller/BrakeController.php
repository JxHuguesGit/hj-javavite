<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\BrakeEvent;
use src\Entity\Game;
use src\Entity\Player;

class BrakeController extends GameController
{

    public static function displayBrakes(Game $objGame): array
    {
        $brakeEventCollection = $objGame->getEventCollection()->getClassEvent(BrakeEvent::class);
        $controller = new BrakeController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_BRAKE,
                LabelConstant::LBL_TRAIL,
                LabelConstant::LBL_FUEL,
                LabelConstant::LBL_BLOCKED,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_BRAKES,
                $brakeEventCollection->length(),
                $brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BRAKE])->length(),
                $brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TRAIL])->length(),
                $brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_FUEL])->length(),
                $brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->length(),
                ConstantConstant::CST_NBSP,
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '', '', '', '', '',
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
               ]
            )
        ];
    }

    public static function displayPlayerBrake(Player $objPlayer): string
    {
        $brakeEventCollection = $objPlayer->getEventCollection()->getClassEvent(BrakeEvent::class);

        return '<br>Freins : <span class="badge bg-info" title="Total">'.$brakeEventCollection->length()
            .' Consommés</span> - <span class="badge bg-warning" title="Freins">'
            .$brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BRAKE])->length()
            .'</span> - <span class="badge bg-warning" title ="Aspiration">'
            .$brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TRAIL])->length()
            .'</span> - <span class="badge bg-warning" title ="Consommation">'
            .$brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_FUEL])->length()
            .'</span> - <span class="badge bg-danger" title ="Blocage">'
            .$brakeEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->length()
            .'</span>';
    }

}
