<?php defined('_JEXEC') or exit;

?>

<div class="j-main-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Item</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->item as $key => $val) {
    echo '<tr>';
    echo '<td>'.ucfirst($key).'</td>';
    echo '<td>'.$val.'</td>';
    echo '</tr>';
}
            ?>
        </tbody>
    </table>
</div>