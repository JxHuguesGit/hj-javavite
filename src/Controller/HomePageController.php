<?php
namespace src\Controller;

use src\Constant\LabelConstant;
use src\Constant\TemplateConstant;
use src\Entity\LogFile;
use src\Utils\FichierUtils;
use src\Utils\RepertoireUtils;
use src\Utils\SessionUtils;

class HomePageController extends UtilitiesController
{
    private string $targetDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Home';
        $this->targetDirectory = TemplateConstant::LOGS_PATH;
    }

    public function getContentPage(string $msgProcessError=''): string
    {
        $arrLignesNonTraitees = [];
        $logSelection = '';
        $fileSelection = '';
        $blnOk = false;

        if (SessionUtils::isPostSubmitted()) {
            $logSelection = SessionUtils::fromPost('logSelection');
            // On est soit dans le cas d'un upload de fichier ou dans la sélection d'un fichier existant.
            $formAction = SessionUtils::fromPost('formAction');
            if ($formAction=='upload') {
                $maxSize = 100 * 1024; // 100k
                $allowedFiles = ['text/plain'=>'log'];
                $fileName = $_FILES['formFile']['name'];
                $tmpName = $_FILES['formFile']['tmp_name'];
                $mimeType = $this->getMimeType($_FILES['formFile']['tmp_name']);

                if (!isset($_FILES['formFile'])) {
                    echo "Non défini";
                } elseif (filesize($_FILES['formFile']['tmp_name'])>$maxSize) {
                    echo "Trop grand";
                } elseif (!in_array($mimeType, array_keys($allowedFiles))) {
                    echo "Type de fichier non valide";
                } else {
                    $uploadedFile = pathinfo($fileName, PATHINFO_FILENAME) . '.' . $allowedFiles[$mimeType];
                    $filePath = PLUGIN_PATH . $this->targetDirectory . $uploadedFile;
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $logSelection = $fileName;
                    } else {
                        echo 'KO';
                    }
                }
            }
        } else {
            $logSelection = SessionUtils::fromGet('logSelection');
        }
        
        if ($logSelection!='') {
            $dirUtils = new RepertoireUtils($this->targetDirectory);
            $files = $dirUtils->recupererFichiers()->getFiles();
            while ($files->valid()) {
                $file = $files->current();
                $fileName = $file->getFileName();
                if ($fileName==$logSelection) {
                    $fileSelection = $fileName;
                    $blnOk = true;
                }
                $files->next();
            }
        }
        
        if ($blnOk) {
            $objLogFile = new LogFile($this->targetDirectory.$fileSelection);
            $arrLignesNonTraitees = $objLogFile->parse();
            $content = $objLogFile->display();
        } else {
            $content = $this->getListing();
        }

        $attributes = [
            $msgProcessError=='' ? 'd-none' : '',
            $msgProcessError,
            $content,
            !empty($arrLignesNonTraitees) ? '<ul><li>'.implode('</li><li>', $arrLignesNonTraitees).'</li></ul>' : ''
        ];
        return $this->getRender(TemplateConstant::TPL_DASHBOARD_PANEL, $attributes);
    }

    private function getListing(): string
    {
        $dirUtils = new RepertoireUtils($this->targetDirectory);
        $files = $dirUtils->recupererFichiers()->getFiles();

        $content = '';
        while ($files->valid()) {
            $file = $files->current();
            $fileName = $file->getFileName();
            $content .= '<option value="'.$fileName.'">'.substr($fileName, 0, -4).'</option>';
            $files->next();
        }
        return $this->getRender(TemplateConstant::TPL_CHANGELOG, [$content]);
    }

    private function getMimeType(string $filename): string
    {
        $info = finfo_open(FILEINFO_MIME_TYPE);
        if (!$info) {
            return false;
        }
    
        $mime_type = finfo_file($info, $filename);
        finfo_close($info);
    
        return $mime_type;
    }

}
