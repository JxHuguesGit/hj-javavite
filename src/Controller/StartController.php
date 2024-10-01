<?php
namespace src\Controller;

use src\Constant\ConstantConstant;
use src\Constant\LabelConstant;
use src\Constant\StyleConstant;
use src\Constant\TemplateConstant;
use src\Entity\Game;
use src\Entity\Player;
use src\Entity\StartTest;

class StartController extends GameController
{
    public static function displayStart(Game $objGame): array
    {
        $startEventCollection = $objGame->getEventCollection()->getClassEvent(StartTest::class);
        $controller = new StartController($objGame);

        return [
            $controller->getRow([
                ConstantConstant::CST_NBSP,
                LabelConstant::LBL_THROWN_DICE,
                LabelConstant::LBL_FAIL_START,
                LabelConstant::LBL_SUCCESS_START,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ConstantConstant::CST_NBSP,
                ],
                false,
                array_fill(0, 7, $controller->getClass([StyleConstant::CSS_BG_DARK, StyleConstant::CSS_TXT_WHITE]))
            ),
            $controller->getRow([
                LabelConstant::LBL_START,
                $startEventCollection->length(),
                $startEventCollection->filter([ConstantConstant::CST_FAIL=>true])->length(),
                $startEventCollection->filter([ConstantConstant::CST_SUCCESS=>true])->length(),
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
            ),
        ];
    }

    public static function displayPlayerStart(Player $objPlayer): string
    {
        $startEventCollection = $objPlayer->getEventCollection()->getClassEvent(StartTest::class);
        
        $quantity = $startEventCollection->length();
        $fail = $startEventCollection->filter([ConstantConstant::CST_FAIL=>true])->length();
        $success = $startEventCollection->filter([ConstantConstant::CST_SUCCESS=>true])->length();

        return 'Départ : <span class="badge bg-info">'.$quantity.sprintf(' Jet%1$s effectué%1$s', $quantity>1 ? 's' : '')
            .'</span> - <span class="badge bg-danger">'.$fail.' Calage'.($fail>1 ? 's' : '')
            .'</span> - <span class="badge bg-success">'.$success.sprintf(' Super%1$s départ%1$s', $success>1 ? 's' : '')
            .'</span>';
    }

}
