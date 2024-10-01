<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\FuelEvent;
use src\Entity\Game;
use src\Entity\Player;

class FuelController extends GameController
{

    public static function displayFuel(Game $objGame): array
    {
        $fuelEventCollection = $objGame->getEventCollection()->getClassEvent(FuelEvent::class);
        $controller = new FuelController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_1GEAR,
                LabelConstant::LBL_2GEAR,
                LabelConstant::LBL_3GEAR,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_FUELS,
                $fuelEventCollection->length(),
                $fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_1GEAR])->length(),
                $fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_2GEAR])->length(),
                $fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_3GEAR])->length(),
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

    public static function displayPlayerFuel(Player $objPlayer): string
    {
        $fuelEventCollection = $objPlayer->getEventCollection()->getClassEvent(FuelEvent::class);

        return '<br>Consommation : <span class="badge bg-info" title="Total">'.$fuelEventCollection->length()
            .'</span> - <span class="badge bg-success" title="1 rapport">'
            .$fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_1GEAR])->length()
            .'</span> - <span class="badge bg-warning" title ="2 rapports">'
            .$fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_2GEAR])->length()
            .'</span> - <span class="badge bg-danger" title ="3 rapports">'
            .$fuelEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_3GEAR])->length()
            .'</span>';
    }

}
