<?php $datatable = 'logs'; ?>

<h1 class="page-header">
    <?php echo $lang['logs']; ?>
</h1>
<div class="content-panel">
    <div class="small-padding">
        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?php echo $lang['log'] . ' ' . $lang['id'] ?></th>
                <th><?php echo $lang['time']; ?></th>
                <th><?php echo $lang['user']; ?></th>
                <th><?php echo $lang['action']; ?></th>
                <th><?php echo $lang['level']; ?></th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th><?php echo $lang['log'] . ' ' . $lang['id'] ?></th>
                <th><?php echo $lang['time']; ?></th>
                <th><?php echo $lang['user']; ?></th>
                <th><?php echo $lang['action']; ?></th>
                <th><?php echo $lang['level']; ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>