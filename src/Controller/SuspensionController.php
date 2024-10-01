<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\Game;
use src\Entity\SuspensionTest;


class SuspensionController extends GameController
{

    public static function displaySuspension(Game $objGame): string
    {
        $controller = new SuspensionController($objGame);

        $suspensionEventCollection = $objGame->getEventCollection()->getClassEvent(SuspensionTest::class);

        $content = '';
        for ($i=1; $i<=10; $i++) {
            $content .= $controller->getRow([
                $i,
                $suspensionEventCollection->filter([ConstantConstant::CST_SCORE=>$i])->length(),
                $i+10,
                $suspensionEventCollection->filter([ConstantConstant::CST_SCORE=>$i+10])->length()],
                true,
                [
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    $i<=4 ? $controller->getClass([StyleConstant::CSS_BG_DANGER]) : '',
                    $controller->getClass([StyleConstant::CSS_BG_LIGHT]),
                    ''
                ]
            );
        }

        $style = $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]);
        $attributes = [
            LabelConstant::LBL_SUSPENSION,
            $controller->getRow([
                LabelConstant::LBL_THROWN_DICE,
                LabelConstant::LBL_FAILED_DICE],
                false,
                array_fill(0, 3, $style)
            ),
            $controller->getRow([
                $suspensionEventCollection->length(),
                $suspensionEventCollection->filter([ConstantConstant::CST_FAIL=>true])->length()],
                false),
            $controller->getRow([
                LabelConstant::LBL_THROW,
                LabelConstant::LBL_QUANTITY,
                LabelConstant::LBL_THROW,
                LabelConstant::LBL_QUANTITY],
                false,
                array_fill(0, 4, $style)),
            $content
        ];
        return $controller->getRender(TemplateConstant::TPL_CARD_DOUBLE_TABLE, $attributes);
    }

}
