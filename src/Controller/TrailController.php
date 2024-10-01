<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\Game;
use src\Entity\Player;
use src\Entity\TrailEvent;

class TrailController extends GameController
{

    public static function displayTrails(Game $objGame): array
    {
        $trailEventCollection = $objGame->getEventCollection()->getClassEvent(TrailEvent::class);
        $controller = new TrailController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_TAKEN_TRAIL,
                LabelConstant::LBL_DECLINED_TRAIL,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_TRAILS,
                $trailEventCollection->length(),
                $trailEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_ACCEPTED])->length(),
                $trailEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_DECLINED])->length(),
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '', '', '',
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
                    $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]),
               ]
            )
        ];
    }

    public static function displayPlayerTrail(Player $objPlayer): string
    {
        $trailEventCollection = $objPlayer->getEventCollection()->getClassEvent(TrailEvent::class);

        return '<br>Aspirations : <span class="badge bg-info" title="Total">'.$trailEventCollection->length()
            .' Proposées</span> - <span class="badge bg-success" title="Acceptées">'
            .$trailEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_ACCEPTED])->length()
            .'</span> - <span class="badge bg-danger" title ="Déclinées">'
            .$trailEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_DECLINED])->length()
            .'</span>';
    }

}
