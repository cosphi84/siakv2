<?php

defined('_JEXEC') or exit;

JFormHelper::loadFieldClass('list');

class JFormFieldSemestertranskip extends JFormFieldList
{
    protected $type = 'Semestertranskrip';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select(['s.id', 's.title'])
            ->from($db->qn('#__siak_semester', 's'))
        ;

        $query->leftJoin('#__siak_nilai AS n ON n.semester = s.id');
        $query->group('n.semester');
        $query->where($db->qn('n.user_id').' = '.(int) $user->id);

        $db->setQuery($query);

        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            $app->enqueueMessage($e->getMessage(), 'error');

            return false;
        }

        foreach ($result as $key => $val) {
            $opts[] = JHtmlSelect::option($val->id, $val->title);
        }

        if ($this->loadExternally) {
            $opts = array_merge(parent::getOptions(), $opts);
        }

        return $opts;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;

        return $this->getOptions();
    }
}
