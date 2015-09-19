<div class="modal fade" id="changeDB" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i>
                    Switch Database
                </h4>
            </div>
            <section class="task-panel tasks-widget">
                <div class="panel-heading">
                    <div class="pull-left"><h5><i class="fa fa-tasks"></i> <?php echo $lang['database'] . 's'  ?></h5></div>
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
</div>
<script src="<?php echo $settings['url'] ?>assets/js/main.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
<?php if (isset($_SESSION['forum_lang'])) echo '<script async type="text/javascript" src="' . $settings["url"] . 'assets/js/language/' . $_SESSION['forum_lang'] . '.js"></script>'; ?>
<script>
    function searchpage() {
        sn = document.getElementById('searchText').value;
        redirecturl = '<?php echo $settings["url"] . $currentPage?>/' + sn;
        document.location.href = redirecturl;
    }
</script>
<?php if(isset($datatable)) { ?>
<script>
    var oTable;
    oTable = $('#datatable').DataTable( {
        "ajax": "<?php echo $settings['url'] . 'hooks/table.php?' . $datatable ?>",
        responsive: true,
        "aoColumnDefs": [
            {
                "bSortable": false,
                "aTargets": ["nosort"]
            },
            {
                "bSearchable": false,
                "aTargets": ["nosearch"]
            }
        ],
        "columnDefs": [ {
            "targets": 0,
            "data": "download",
            "render": function ( data, type, full, meta ) {
                return '<a href="'+data+'">Download</a>';
            }
        } ]
    } );
</script>
<?php } ?>
<script type="text/javascript">
    $('#myTab a').click(function (e) {
        console.log('clicked ' + this);
        if ($(this).parent('li').hasClass('active')) {
            var target_pane = $(this).attr('href');
            console.log('pane: ' + target_pane);
            $(target_pane).toggle(!$(target_pane).is(":visible"));
        }
    });
</script>
