<?php $datatable = 'vehicles'; ?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['vehicles']; ?>
        </h1>
    </div>
</div>
<div class="content-panel">
    <div class="small-padding">
        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?php echo $lang['car'] . ' ' . $lang['id']; ?></th>
                <th><?php echo $lang['owner']; ?></th>
                <th><?php echo $lang['class']; ?></th>
                <th><?php echo $lang['type']; ?></th>
                <th><?php echo $lang['plate']; ?></th>
                <th class="nosearch"><?php echo $lang['alive']; ?></th>
                <th class="nosearch"><?php echo $lang['active']; ?></th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th><?php echo $lang['car'] . ' ' . $lang['id']; ?></th>
                <th><?php echo $lang['owner']; ?></th>
                <th><?php echo $lang['class']; ?></th>
                <th><?php echo $lang['type']; ?></th>
                <th><?php echo $lang['plate']; ?></th>
                <th><?php echo $lang['alive']; ?></th>
                <th><?php echo $lang['active']; ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
