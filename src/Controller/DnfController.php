<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\DnfEvent;
use src\Entity\Game;

class DnfController extends GameController
{
    public static function displayDnfs(Game $objGame): array
    {
        $dnfEventCollection = $objGame->getEventCollection()->getClassEvent(DnfEvent::class);
        $controller = new DnfController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_BODY,
                LabelConstant::LBL_SUSP,
                LabelConstant::LBL_ENGINE,
                LabelConstant::LBL_BLOCKED,
                LabelConstant::LBL_TIRE
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_DNFS,
                $dnfEventCollection->length(),
                $dnfEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BODY])->length(),
                $dnfEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_SUSPENSION])->length(),
                $dnfEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_ENGINE])->length(),
                $dnfEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_BLOCKED])->length(),
                $dnfEventCollection->filter([ConstantConstant::CST_TYPE=>ConstantConstant::CST_TIRE])->length(),
                ],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    '', '', '',
                    '', '', '',
                ]
            ),
        ];
    }

}
