<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\Game;
use src\Entity\Player;
use src\Entity\TaqEvent;

class TaqController extends GameController
{

    public static function displayTaQ(Game $objGame): array
    {
        $taqEventCollection = $objGame->getEventCollection()->getClassEvent(TaqEvent::class);
        $controller = new TaqController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_QUANTITY,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_TAQ,
                $taqEventCollection->length(),
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '',
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
               ]
            )
        ];
    }

    public static function displayPlayerTaQ(Player $objPlayer): string
    {
        $taqEventCollection = $objPlayer->getEventCollection()->getClassEvent(TaqEvent::class);
        $nb = $taqEventCollection->length();

        return '<br>Tête à queue : <span class="badge bg-'.($nb==0?'success':'danger').'">'.$nb.'</span>';
    }
}
