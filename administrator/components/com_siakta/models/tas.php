<?php
/**
 * @package     Joomla.Siak
 * @subpackage  SIAK TA
 *
 * @copyright   (C) 2022 @ Risam, S.T
 * @license     Limited for FT-UNTAG Cirebon use Only
 */

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

 defined('_JEXEC') or die;


 class SiaktaModelTas extends ListModel
 {
     /**
      * Constructor
      *
      * @param $config Array
      */
     public function __construct($config = array())
     {
         if (empty($config['filter_fields'])) {
             $config['filter_fields'] = array(
                'id', 'prodi', 'konsentrasi', 'published',
                'd.name', 'd.username', 'b.prodi', 'a.tahun'
            );
         }
         parent::__construct($config);
     }

     /**
      * Get List Query
      *
      */
     protected function getListQuery()
     {
         $db = $this->getDbo();
         $query = $db->getQuery(true);
         $search = $this->getState('filter.search');
         $prodi = $this->getState('filter.prodi');
         $jurusan = $this->getState('filter.konsentrasi');
         $state = $this->getState('filter.published');


         $query->select('a.*')
            ->from($db->quoteName('#__siak_ta', 'a'));

         $query->select('b.title AS prodi')
            ->leftJoin('#__siak_prodi AS b ON b.id = a.prodi_id');

         $query->select('c.title AS jurusan')
            ->leftJoin('#__siak_jurusan AS c ON c.id = a.konsentrasi_id');

         $query->select(array('d.name AS mahasiswa', 'd.username AS npm'))
            ->leftJoin('#__users AS d ON d.id = a.mahasiswa_id');

         if (is_numeric($prodi)) {
             $query->where($db->quoteName('a.prodi_id'). ' = '. (int) $prodi);
         }

         if (is_numeric($jurusan)) {
             $query->where($db->quoteName('a.konsentrasi_id'). ' = '. (int) $jurusan);
         }

         if (!empty($search)) {
             $search = $db->quote('%'. $search .'%');
             $searchs = array(
                $db->quoteName('d.name'). ' LIKE '. $search,
                $db->quoteName('d.username'). ' LIKE '. $search,
                $db->quoteName('a.title'). ' LIKE '. $search,
             );

             $query->where('('. implode(' OR ', $searchs). ')');
         }
         
         if(is_numeric($state))
         {
            $query->where($db->quoteName('a.state'). ' = '. $db->quote($state));
         }elseif($state === ''){
            $query->where($db->quoteName('a.state'). ' IN (0,1)');
         }

         $orderCol = $this->getState('list.ordering', 'a.id');
         $orderDir = $this->getState('list.direction', 'DESC');
         $query->order($db->escape($orderCol). ' '. $db->escape($orderDir));

        
         return $query;
     }

     protected function populateState($ordering = 'a.id', $direction = 'DESC')
     {
         $app = Factory::getApplication();
         $format = $app->input->get('format', 'html');

         parent::populateState($ordering, $direction);
         
         if ($format !== 'html') {
             $this->setState('list.limit', '');
         }
     }
 }
