<?php

use Joomla\CMS\Date\Date;

defined('_JEXEC') or exit;

class SiakControllerBayar extends JControllerAdmin
{
    public function cancel($key = null)
    {
        $this->checkToken();
        $this->setRedirect(JRoute::_('index.php?option=com_siak&view=dashboard', false), JText::_('COM_SIAK_ADD_CANCELLED'));

        return true;
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel('Bayar');

        $user = JFactory::getUser();
        $npm = $user->username;
        $path = JPATH_ROOT.'/media/com_siak/files/kuitansi/';
        $date = new Date('now');
        $uri = JRoute::_('index.php?option=com_siak&view=bayaran', false);
        $data = $input->get('jform', [], 'array');
        $files = $input->files->get('jform', [], 'array');
        $foto = $files['kuitansi'];

        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }
        $validData = $model->validate($form, $data);
        if (false === $validData) {
            $errors = $model->getErrors();

            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $this->setRedirect($uri);

            return false;
        }
        $validData['user_id'] = $user->id;
        $validData['created_time'] = $date->toSql();

        if (0 == $foto['error'] && $foto['size'] > 0) {
            // process upload
            jimport('joomla.filesystem.file');
            $fileName = JFile::makeSafe($foto['name']);
            $ext = '.'.strtolower(JFile::getExt($fileName));
            $namaFoto = $npm.'_'.$validData['no_ref'].$ext;
            $pathFoto = $path.$namaFoto;

            if (JFile::exists($pathFoto)) {
                $app->enqueueMessage(JText::plural('COM_SIAK_ERROR_FILE_EXIST_UPLOAD_FILE', $validData['no_ref']), 'error');
                $this->setRedirect($uri, false);

                return false;
            }

            if (!JFile::upload($foto['tmp_name'], $pathFoto)) {
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');

                return false;
            }

            $image = new JImage($pathFoto);
            $prop = JImage::getImageFileProperties($pathFoto);
            $arah = $image->getOrientation();
            if ('portrait' == $arah) {
                $image->rotate(90, -1, false);
            }
            $w = 1028;
            $h = 573;
            $newImg = $image->resize($w, $h, false);
            $mime = $prop->mime;

            if ('image/jpeg' == $mime) {
                $type = IMAGETYPE_JPEG;
            } elseif ('image/png' == $mime) {
                $type = IMAGETYPE_PNG;
            } elseif ('image/gif' == $mime) {
                $type = IMAGETYPE_GIF;
            }
            $newImg->toFile($pathFoto, $mime);

            $validData['kuitansi'] = $namaFoto;
        }

        if (!$model->save($validData)) {
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect($uri, false);

            return false;
        }

        $this->setRedirect(
            $uri,
            JText::_('COM_SIAK_ADD_SUCCESSFUL')
        );

        return true;
    }
}
