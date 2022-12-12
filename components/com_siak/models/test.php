<?php

defined('_JEXEC') or die('Mati di model');

/**
 * @internal
 * @coversNothing
 */
class SiakModelTest extends JModelAdmin
{
    public function getTable($type = 'User', $prefix = 'Table', $config = [])
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm('siak.krs', 'krs', ['control' => 'jform', 'load_data' => $loadData]);
        if (empty($form)) {
            return false;
        }

        return $form;
    }
}
