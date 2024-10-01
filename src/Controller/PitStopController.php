<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\Game;
use src\Entity\PitStopTest;
use src\Entity\Player;

class PitStopController extends GameController
{
    public static function displayPitStops(Game $objGame): array
    {
        $pitStopTestCollection = $objGame->getEventCollection()->getClassEvent(PitStopTest::class);
        $controller = new PitStopController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_PIT_STOP,
                LabelConstant::LBL_LONG_PIT_STOP,
                LabelConstant::LBL_SUCCESS_SHORT_PIT_STOP,
                LabelConstant::LBL_FAIL_SHORT_PIT_STOP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_PITS,
                $pitStopTestCollection->length(),
                $pitStopTestCollection
                    ->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_LONG_STOP])
                    ->length(),
                $pitStopTestCollection->filter([
                    ConstantConstant::CST_TYPE=>ConstantConstant::CST_SHORT_STOP,
                    ConstantConstant::CST_FAIL=>false])->length(),
                $pitStopTestCollection->filter([
                    ConstantConstant::CST_TYPE=>ConstantConstant::CST_SHORT_STOP,
                    ConstantConstant::CST_FAIL=>true])->length(),
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '', '', '', '',
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                ]
            ),
        ];
    }

    public static function displayPlayerPitStop(Player $objPlayer): string
    {
        $pitStopTestCollection = $objPlayer->getEventCollection()->getClassEvent(PitStopTest::class);

        return '<br>Stands : <span class="badge bg-info" title="Nombre d\'arrêts">'
            .$pitStopTestCollection->length()
            .'</span> - <span class="badge bg-success" title="Rapides réussis">'
            .$pitStopTestCollection->filter([
                ConstantConstant::CST_TYPE=>ConstantConstant::CST_SHORT_STOP,
                ConstantConstant::CST_FAIL=>false])->length()
            .'</span> - <span class="badge bg-danger" title ="Rapides échoués">'
            .$pitStopTestCollection->filter([
                ConstantConstant::CST_TYPE=>ConstantConstant::CST_SHORT_STOP,
                ConstantConstant::CST_FAIL=>true])->length()
            .'</span>';
    }
}
