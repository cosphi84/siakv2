<?php

defined('_JEXEC') or exit;

use Joomla\CMS\Response\JsonResponse;

class SiakController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        $user = JFactory::getUser();
        $app = Jfactory::getApplication();

        if (1 == $user->get('guest')) {  // Area ini hanya untuk user valid saja.
            $uri = JUri::getInstance();
            $return = base64_encode($uri);
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return='.$return, false));

            return;
        }

        JLoader::register('SiakHelper', JPATH_COMPONENT.'/helpers/siak.php');
        $siak = new SiakHelper();
        $bio = $siak::loadBiodata($user->id);

        if ($siak->isMahasiswa) {
            $app->setUserState('com_siak.mahasiswa.status', $siak->Status);
        }

        if ($bio->reset) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_SIAK_USER_REQUEST_UPDATE_BIO_MSG'), 'warning');
            //JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_siak&view=user&layout=edit', false));
        }

        $document = JFactory::getDocument();
        $vName = $this->input->getCmd('view', 'dashboard');
        $vFormat = $document->getType();
        $lName = $this->input->getCmd('layout', 'default');

        if ($view = $this->getView($vName, $vFormat)) {
            switch ($vName) {
                case 'dashboard':
                    $model = $this->getModel('user');

                    break;

                default:
                $model = $this->getModel($vName);

            break;
            }

            $app->setHeader('Referrer-Policy', 'no-referrer', true);
            $view->setModel($model, true);
            $view->setLayout($lName);
            $view->document = $document;

            $view->display();
        }
    }

    /**
     * exec Melakukan sebuah task dari tiap controler.
     */
    public function exec()
    {
        $app = JFactory::getApplication();

        $user = JFactory::getUser();
        if (1 == $user->get('guest')) {  // Area ini hanya untuk user valid saja.
            echo new JsonResponse(['code' => '401', 'desc' => 'Unauthorized'], JText::_('JERROR_ALERTNOAUTHOR'), true);

            return;
        }

        /*
        if (!JSession::checkToken('get')) {
            echo new JsonResponse(['code' => '406', 'desc' => 'Invalid Token'], JText::_('JINVALID_TOKEN'), true);

            return;
        }
        */

        $input = $app->input->get('var', [], 'array');
        // 2 variabel yang harus ada: Model, Method
        if (!key_exists('model', $input) || !key_exists('method', $input)) {
            echo new JsonResponse(['code' => '417', 'desc' => 'Invalid Data Payload'], JText::_('COM_SIAK_PAYLOAD_ERROR'), true);

            return;
        }

        $var = [];
        $var = $input;
        unset($var['method'], $var['model']);

        $model = $this->getModel($input['model']);
        if (!$model) {
            echo new JsonResponse(['code' => '404', 'desc' => 'Ngga ada model yang diplh'], 'Model yang diplih ngga ada', true);

            return;
        }

        if (!method_exists($model, $input['method'])) {
            echo new JsonResponse(['code' => '404', 'desc' => 'Ngga ada method yang diplh'], 'Method ini ngga ada di model yang diplih!', true);

            return;
        }

        $result = $model->{$input['method']}($var);

        $error = $model->getErrors();
        if (count($error) > 0) {
            echo new JsonResponse(null, implode('\n', $error), true);

            return;
        }

        echo new JsonResponse($result);

        return true;
    }
}
