<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['navDashboard']; ?>
        </h1>
    </div>
</div>
<?php if (isset($_SESSION['update'])) echo '<div class="alert alert-info" role="alert">' . $lang['updateMessage'] . ' (' . $_SESSION['message']->version . ')</div>'; ?>

<div class="row mt mb">
    <div class="col-md-12">
        <section class="task-panel tasks-widget">
            <div class="panel-heading">
                <div class="pull-left"><h5><i class="fa fa-tasks"></i> Your Servers</h5></div>
                <br>
            </div>
            <div class="panel-body">
                <div class="task-content">
                    <ul id="sortable" class="task-list ui-sortable">
                        <?php foreach ($_SESSION['servers'] as $server) { ?>
                            <li class="list-primary">
                                <i class=" fa fa-ellipsis-v"></i>

                                <div class="task-title">
                                    <span class="task-title-sp"><?php echo $server['name']; ?></span>
                                    <?php
                                    if ($server['type'] == 'life') {
                                        echo '<span class="badge bg-theme">Life</span>';
                                    } elseif ($server['type'] == 'waste') {
                                        echo '<span class="badge bg-important">Wasteland</span>';
                                    }
                                    ?>
                                    <div style="float:right; padding-right: 15px;">
                                        <a href="<?php echo $settings['url'] . 'dashboard?id=' . $server['sid']; ?>"
                                           class="btn btn-success btn-sm fa fa-eye" type="submit"></a>
                                    </div>
                                </div>
                            </li>
                        <?php }
                        echo '</select>'; ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include("views/templates/news.php");
