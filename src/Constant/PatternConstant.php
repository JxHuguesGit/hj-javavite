<?php
namespace src\Constant;

interface PatternConstant
{
    public const FR_PATTERN_DNF           = '/(.*) est élimin/';
    public const FR_PATTERN_PNEUS         = '/(.*) sort du virage en dérapant de {1,2}(\d+) .*pneus (.*)/';
    public const FR_PATTERN_DNF2          = '/(.*) est parti dans les graviers/';
    public const FR_PATTERN_CONSO         = '/(.*) rétrograde(.*)endommage sa boîte de vitesse/';
    public const FR_PATTERN_CONSO2        = '/(.*) fait hurler(.*)endommage sa boîte de vitesse/';
    public const FR_PATTERN_FREIN         = '/(.*) ecrase sa pédale de frein pour ne pas avancer trop/';
    public const FR_PATTERN_ASPI          = '/(.*) peut profiter de l.aspiration sur (.*)/';
    public const FR_PATTERN_LATE_BRK      = '/(.*) freine en entrée de virage suite à l\'aspiration/';
    public const FR_PATTERN_TAQ           = '/(.*) fait un tête à queue en sortie de virage/';
    public const FR_PATTERN_BLOCKED       = '/(.*) est bloqué : accident automatique avec (.*)/';

    public const FR_PATTERN_MOVE          = '/(.*) passe la (.*) et fait (\d*) au/';
    public const FR_PATTERN_WINNER        = '/(.*) remporte la course/';
    public const FR_PATTERN_FINISH        = '/(.*) franchit la ligne d/';
    public const FR_PATTERN_PITSTOP       = '/(.*) s.arrête aux stands/';
    public const FR_PATTERN_BRK_CCL       = '/(.*) choisit finalement de ne pas appuyer sur le frein/';

    public const FR_PATTERN_TEST          = '/(.*) : Test (.*) : Jet = (\d*)(.*requis ([<>\d]*))?/';
    public const FR_PATTERN_METEO         = '/(.*)jet = (\d*).*(et|<|>).*/';

    public const FR_PATTERN_LONG_STOP     = '/(.*) choisit un arrêt long/';
    public const FR_PATTERN_SHORT_FAIL    = '/(.*) termine son tour à enguirlander les mécanos . \(jet = (\d*) /';
    public const FR_PATTERN_SHORT_SUCC    = '/(.*) repart immédiatement des stands . \(jet = (\d*) /';

}
