<?php

defined('_JEXEC') or exit;

use Joomla\Utilities\ArrayHelper;

class SiakModelBayaran extends JModelAdmin
{
    protected $context = 'com_siak.bayaran';

    public function getTable($type = 'Pembayaran', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm($this->context, 'bayaran', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    public function getItem($pk = null)
    {
        $db = JFactory::getDbo();
        $id = JFactory::getApplication()->input->get('id', 0, 'int');
        $query = $db->getQuery(true);

        $query->select(['u.name as mahasiswa', 'u.username as NPM', 'p.*'])
            ->from($db->qn('#__siak_pembayaran', 'p'))
            ->leftJoin('#__users AS u ON u.id=p.user_id')
            ->where($db->qn('p.id').' = '.(int) $id)
        ;
        $db->setQuery($query);

        try {
            $result = $db->loadObject();
        } catch (\Throwable $th) {
            $this->setError($th->getMessage());

            return false;
        }

        return $result;
    }

    /**
     * lunas
     * Set Status pembayaran apakah sudah lunas atau belum.
     *
     * @param null|mixed $pk    Record ID yang yang akan di set pembayarannya
     * @param mixed      $lunas 0 => belum Bayar, 1 => belum lunas, 2 => sudah lunas
     */
    public function lunas($pk = null, $lunas = 2)
    {
        $table = $this->getTable();
        $return = $table->load($pk);

        // Check for a table object error.
        if (false === $return && $table->getError()) {
            $this->setError($table->getError());

            return false;
        }

        $properties = $table->getProperties(1);
        $item = ArrayHelper::toObject($properties, '\JObject');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $kondisi = [
            $db->qn('user_id').' = '.$db->q($item->user_id),
            $db->qn('semester').' = '.$db->q($item->semester),
            $db->qn('pembayaran').' = '.$db->q($item->pembayaran),
        ];
        $query->update($db->qn('#__siak_pembayaran'))
            ->set($db->qn('lunas').' = '.(int) $lunas)
            ->where($kondisi)
        ;

        try {
            $db->setQuery($query);
            $db->execute();
        } catch (RuntimeException $err) {
            $this->setError($err->getMessage());

            return false;
        }

        return true;
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }
}
