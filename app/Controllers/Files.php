<?php
namespace Controllers;

use Entity\File;
use Entity\User;
use System\Crypt;
use Entity\Friend;
use Models\File as ModelFile;
use Exceptions\NotFoundException;

/**
 * Class Download
 * @package Controllers
 */
class Files extends Controller
{
    protected function before()
    {
        $this->checkAuthorization();
    }

    /**
     * @param int|null $id - id файла
     * @throws NotFoundException
     */
    protected function actionDownload(?int $id)
    {
        $file = File::get(['id' => $id]);
        if (empty($file) || empty($file->getId())) throw new NotFoundException('File not found');

        // TODO проверить разрешения на скачивание

        $user = Friend::get(['id' => $file->getUserId()]);
        if (!User::checkUser($user)) throw new NotFoundException('User not found');

        $crypt = new Crypt($user->getPublicKey());
        $fileName = $crypt->decryptByPublicKey($file->getFileName());
        $fileLink = $crypt->decryptByPublicKey($file->getFileLink());
        $this->sendFile(DIR_PUBLIC . $fileLink, $fileName);
    }

    /**
     * @param string $fileLink - ссылка на файл
     * @param string|null $fileName - имя файла
     * @throws NotFoundException
     */
    private function sendFile(string $fileLink, ?string $fileName = null)
    {
        if (!ModelFile::checkFile($fileLink)) throw new NotFoundException('File not found');

        if (ob_get_level()) ob_end_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: ' . mime_content_type($fileLink));
        header('Content-Disposition: attachment; filename=' . basename($fileName ?: $fileLink));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileLink));
        readfile($fileLink);
        die;
    }

    private function checkPermissions($id, $user)
    {}
}
