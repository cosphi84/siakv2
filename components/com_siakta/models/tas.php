<?php
/**
 * @package     Joomla.Siak
 * @subpackage  SIAK TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */
 defined('_JEXEC') or die;
 
 use Joomla\CMS\MVC\Model\ListModel;
 use Joomla\CMS\Factory;
 use Joomla\CMS\Component\ComponentHelper;

 class SiaktaModelTas extends ListModel
 {
     public function __construct($config = array())
     {
         if (empty($config['filter_fields'])) {
             $config['filter_fields'] = array(
                'id', 'judul',
                'a.judul', 'u.name', 'a.tahun'
             );
         }

         parent::__construct($config);
    
         $user = Factory::getUser();
         $userGroups = $user->get('groups');

         $siakParams = ComponentHelper::getParams('com_siak');
         $grpMahasiswa = $siakParams->get('grpMahasiswa');

         if (in_array($grpMahasiswa, $userGroups)) {
             $this->setState('mode', 'mahasiswa');
             $this->setState('user_id', $user->id);
         } else {
             $this->setState('mode', 'public');
         }
     }

     public function getListQuery()
     {
         $db = $this->getDbo();
         $query = $db->getQuery(true);
         $mode = $this->getState('mode', 'mahasiswa');
         $search = $this->getState('filter.search');
         empty($this->getState('user_id')) ?  $uid = Factory::getUser()->id : $uid = $this->getState('user_id');

         $query->select('a.*')
            ->from($db->quoteName('#__siak_ta', 'a'));
         $query->select(array('u.username as NPM', 'u.name AS mahasiswa'))
                ->leftJoin('#__users AS u ON u.id = a.mahasiswa_id');

         if (!empty($search)) {
             $search = $db->quote('%'.$search .'%');
             $searchs = array();
             $searchs[] = $db->quoteName('u.name'). ' LIKE '. $search;
             $searchs[] = $db->quoteName('u.username'). ' LIKE '. $search;
             $searchs[] = $db->quoteName('a.title'). ' LIKE '. $search;
               
             $query->where('('. implode(' OR ', $searchs). ')');
         }
         
         $query->select('p.title as prodi')
            ->leftJoin('#__siak_prodi AS p ON p.id = a.prodi_id');

         $query->select('j.title AS jurusan')
         ->leftJoin('#__siak_jurusan AS j ON j.id = a.konsentrasi_id');

         if ($mode === 'mahasiswa') {
             if (empty($search)) {
                 $query->where($db->quoteName('a.mahasiswa_id'). ' = '. (int) $uid);
             }
         } else {
             $query->where($db->quoteName('a.state'). ' > 0');
         }
         
         $orderCol	= $this->state->get('list.ordering', 'a.id');
         $orderDirn 	= $this->state->get('list.direction', 'DESC');
    
         $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
    
         return $query;
     }
 }
