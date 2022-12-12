<?php

defined('_JEXEC') or exit;

class SiakModelRevisinilai extends JModelForm
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('com_siak.revisinilai', 'revisinilai', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getData()
    {
        $db = Jfactory::getDbo();
        $query = $db->getQuery(true);
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'int');
        $query->select(['r.*'])
            ->from($db->qn('#__siak_revisi_nilai', 'r'))
        ;
        $query->where($db->qn('r.id').' = '.(int) $id);

        try {
            $db->setQuery($query);
            $result = $db->loadResult();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        return $result;
    }

    public function loadFormData()
    {
        return $this->getData();
    }

    public function getTable($name = 'Revisinilai', $prefix = 'SiakTable', $config = [])
    {
        return parent::getTable($name, $prefix, $config);
    }

    public function save($data = [])
    {
        $pk = $data['id'];

        $table = $this->getTable();

        try {
            $table->load($pk);

            if (!$table->bind($data)) {
                $this->setError($table->getError());

                return false;
            }

            if (!$table->check()) {
                $this->setError($table->getError());

                return false;
            }

            if (!$table->store()) {
                $this->setError($table->getError());

                return false;
            }

            $this->cleanCache();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }

        return true;
    }
}
