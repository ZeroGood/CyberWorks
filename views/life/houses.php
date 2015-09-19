<?php $datatable = 'houses'; ?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['houses']; ?>
        </h1>
    </div>
</div>
<div class="content-panel">
    <div class="small-padding">
        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?php echo $lang['house'] . ' ' . $lang['id']; ?></th>
                <th><?php echo $lang['name']; ?></th>
                <th><?php echo $lang['playerID']; ?></th>
                <th><?php echo $lang['position']; ?></th>
                <th class="nosearch"><?php echo $lang['owned']; ?></th>
            </tr>
            </thead>

            <tfoot>
            <tr>
                <th><?php echo $lang['house'] . ' ' . $lang['id']; ?></th>
                <th><?php echo $lang['name']; ?></th>
                <th><?php echo $lang['playerID']; ?></th>
                <th><?php echo $lang['position']; ?></th>
                <th><?php echo $lang['owned']; ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
