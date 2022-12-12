<?php defined('_JEXEC') or exit;

$baseDir = JPATH_ROOT.'/media/com_siak/files/kuitansi/';
$kuitansi = $this->item->kuitansi;

echo '<div class="j-main-container">';
echo '<div class="container">';
if (JFile::exists($baseDir.$kuitansi)) {
    $file = JURI::root().'/media/com_siak/files/kuitansi/'.$kuitansi;
    echo '<img src="'.$file.'" alt='.$kuitansi.'"/>';
} else {
    echo 'Not Found!';
}

?>
</div>
<div class="center">
    <button class="btn-primary" onclick="window.close();">
        <span class="icon-delete"></span><?php echo JText::_('JTOOLBAR_CLOSE'); ?>
    </button>
</div>
</div>