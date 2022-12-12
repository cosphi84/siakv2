<?php

defined('_JEXEC') or die;

?>
<div class="container fluid">
    <div class="page-heading">
        <h2><?php echo JText::_('COM_SIAK_USER_BIODATA_PAGE_HEADING'); ?>
        </h2>
        <hr>
    </div>
    <div class="clearsfx"></div>

    <div class="biodata">
        <table class="table table-padding well">
            <thead>
                <tr>
                    <th clas="nowrap"><?php echo JText::_('COM_SIAK_USER_BIODATA_OPTION_TITLE'); ?>
                    </th>
                    <th clas="nowrap"><?php echo JText::_('COM_SIAK_USER_BIODATA_VALUE_TITLE'); ?>
                    </th>
                </tr>

            </thead>
            <?php
                $type = 'Dosen';

                $user = JFactory::getUser($this->item['user_id'])->get('groups');

                if (in_array($grpMhs, $user)) {
                    // maahasiswa
                    $type = 'mahasiswa';
                }
                ?>
            <tbody>
            </tbody>
        </table>
    </div>
</div>