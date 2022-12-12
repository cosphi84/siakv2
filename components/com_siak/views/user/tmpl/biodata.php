<?php

use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');

?>

<div class="container-fluid">
    <legend>
        <?php echo JText::_('COM_SIAK_BIODATA_HEDAER'); ?>
    </legend>
    <div class="clearfix"></div>

    <div class="row-fluid well">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th><i>Value</i></th>
                </tr>
            <tbody>
                <?php
                $hide = ['prodi' => 1, 'kelas' => 1, 'jurusan' => 1, 'id' => 1, 'user_id' => 1, 'tipe_user' => 1, 'reset' => 1, 'checked_out' => 1, 'checked_out_time' => 1, 'asset_id' => 1, 'last_update' => 1, 'tanggal_masuk' => 1, 'tanggal_keluar' => 1];
                $gid = JFactory::getUser()->get('groups');
                $userMhs = $this->params->get('grpMahasiswa');
                if (in_array($userMhs, $gid)) {
                    // mahasiswa
                    $hide['nidn'] = 1;
                    $hide['nik'] = 1;
                } else {
                    $hide['angkatan'] = 1;
                }

                $dataObj = ArrayHelper::fromObject($this->item);

                $data = array_diff_key($dataObj, $hide);
                $row = 1;

                    foreach ($data as $key => $val) {
                        'no_ktp' == $key ? $key = 'No KTP' : $key;
                        ('nidn' == $key) || ('nik' == $key) ? $key = strtoupper($key) : $key;
                        'dob' == $key ? $key = 'Tanggal Lahir' : $key;
                        'pob' == $key ? $key = 'Tempat Lahir' : $key;
                        'alamat_2' == $key ? $key = 'Alamat (Lanjutan)' : $key;
                        echo '<tr class="row'.($row % 2).'">';
                        echo '<td>';
                        echo ucwords(str_replace('_', ' ', $key)).' :';
                        echo '</td>';
                        echo '<td>';
                        if ('foto' == $key) {
                            if (empty($val)) {
                                if ('LAKI-LAKI' == $this->item->jenis_kelamin) {
                                    echo  '<img src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-l.png" style="width:72px; height:96px; margin-left:15px;"> </td>';
                                } else {
                                    echo  '<img src="'.JURI::root().'media/com_siak/files/foto_user/dummy-user-p.png" style="width:72px; height:96px; margin-left:15px;"> </td>';
                                }
                            } else {
                                echo  '<img src="'.JURI::root().'media/com_siak/files/foto_user/'.$val.'" style="width:72px; height:96px; margin-left:15px;"> </td>';
                            }
                        } else {
                            echo ucfirst($val).'</td>';
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
            </thead>
        </table>
    </div>

</div>