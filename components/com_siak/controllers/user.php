<?php

defined('_JEXEC') or die;
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

class SiakControllerUser extends JControllerForm
{
    public function cancel($key = null)
    {
        parent::cancel($key);

        $this->setRedirect(
            JRoute::_('index.php?option=com_siak&view=user&layout=biodata', false),
            JText::_('COM_SIAK_ADD_CANCELLED')
        );
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel();
        $uid = JFactory::getUser()->id;
        $path = JPATH_ROOT.DS.'media'.DS.'com_siak'.DS.'files'.DS.'foto_user'.DS;

        $currentUri = JRoute::_('index.php?option=com_siak&view=user&layout=biodata', false);

        // Check that this user is allowed to add a new record
        if (!JFactory::getUser()->authorise('core.create', 'com_siak')) {
            $app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            $app->setHeader('status', 403, true);

            return;
        }

        $data = $input->post->get('jform', [], 'array');
        $files = $input->files->get('jform', [], 'array');
        $foto = $files['foto'];

        $context = "{$this->option}.edit.{$this->context}";

        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');

            return false;
        }
        $validData = $model->validate($form, $data);
        $validData['user_id'] = $uid;
        $validData['last_update'] = date('Y-m-d H:i:s');
        $validData['reset'] = '0';
        $validData['id'] = $model->getUser()['id'];

        if (0 == $foto['error'] && $foto['size'] > 0) {
            // process upload
            jimport('joomla.filesystem.file');
            $fileName = JFile::makeSafe($foto['name']);
            $ext = '.'.strtolower(JFile::getExt($fileName));
            $namaFoto = $uid.$ext;
            $pathFoto = $path.$namaFoto;

            if (JFile::exists($pathFoto)) {
                JFile::delete($pathFoto);
            }

            if (!JFile::upload($foto['tmp_name'], $pathFoto)) {
                $app->enqueueMessage(JText::_('COM_SIAK_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');

                return false;
            }

            // force resize foto
            $image = new JImage($pathFoto);
            $prop = JImage::getImageFileProperties($pathFoto);
            $w = 360;
            $h = 480;
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

            $validData['foto'] = $namaFoto;
        }

        if (false === $validData) {
            $errors = $model->getErrors();

            for ($i = 0, $n = count($errors); $i < $n && $i < 3; ++$i) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $app->setUserState($context.'.data', $data);

            $this->setRedirect($currentUri);

            return false;
        }

        if (!$model->save($validData)) {
            $app->setUserState($context.'.data', $validData);

            // Redirect back to the edit screen.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect($currentUri);

            return false;
        }

        $app->setUserState($context.'.data', null);

        $this->setRedirect(
            $currentUri,
            JText::_('COM_SIAK_ADD_SUCCESSFUL')
        );

        return true;
    }
}
