<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\TireEvent;
use src\Entity\Game;
use src\Entity\Player;

class TireController extends GameController
{

    public static function displayTires(Game $objGame): array
    {
        $tireEventCollection = $objGame->getEventCollection()->getClassEvent(TireEvent::class);
        $controller = new TireController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_CURVE_EXIT,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_BLOCKED,
                LabelConstant::LBL_QUANTITY,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_TIRE,
                $tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TIRE])->length(),
                $tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TIRE])->sum(),
                $tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->length(),
                $tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->sum(),
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '', '', '',
                    '',
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
               ]
            )
        ];
    }

    public static function displayPlayerTires(Player $objPlayer): string
    {
        $tireEventCollection = $objPlayer->getEventCollection()->getClassEvent(TireEvent::class);

        return '<br>Pneus : <span class="badge bg-info">'
            .$tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TIRE])->length()
            .' Sorties</span> - <span class="badge bg-danger">'
            .$tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TIRE])->sum()
            .' Consommés</span> - <span class="badge bg-info">'
            .$tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->length()
            .' Blocages</span> - <span class="badge bg-danger">'
            .$tireEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->sum()
            .' Consommés</span>';
    }
    
}
