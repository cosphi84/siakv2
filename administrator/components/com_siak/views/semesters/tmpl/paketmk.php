<?php defined('_JEXEC') or exit;

?>
<div class="container well">
    <table class="table table-striped table-bordered">
        <thead>
            <th>
                #
            </th>
            <th>Kode MK</th>
            <th>Matakuliah</th>
            <th>Bobot SKS</th>
        </thead>
        <tbody>
            <?php $a = 1;
            foreach ($this->mks as $i => $j) { ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td><?php echo $a; ?>
                </td>
                <td><?php echo $j->kodeMK; ?>
                </td>
                <td><?php echo $j->MK; ?>
                </td>
                <td><?php echo $j->sks; ?>
                </td>
                <?php ++$a; ?>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>