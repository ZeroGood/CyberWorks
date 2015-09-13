<?php
$db_link = serverConnect();

$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];

if (isset($search)) {
    $sql = "SELECT `uid` FROM `players` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "';";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `playerid`,`name`,`bankacc`,`cash`,`coplevel`,`mediclevel`,`adminlevel`,`uid` FROM `players` WHERE `uid` LIKE '" . $search . "' OR `name` LIKE '" . $search . "' OR `playerid` LIKE '" . $search . "'" . $max . " ;";
    logAction($_SESSION['user_name'], $lang['searched'] . ' (' . $search . ') ' . $lang['in'] . ' ' . $lang['players'], 1);
} else {
    $sql = "SELECT `uid` FROM `players`;";
    $result_of_query = $db_link->query($sql);
    $total_records = mysqli_num_rows($result_of_query);
    if ($pageNum > $total_records) $pageNum = $total_records;
    $sql = "SELECT `playerid`,`name`,`bankacc`,`cash`,`coplevel`,`mediclevel`,`adminlevel`,`uid` FROM `players` " . $max . " ;";
}

$result_of_query = $db_link->query($sql);
if ($result_of_query->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result_of_query)) {
            $pids[] = $row['playerid'];
        }
        $pids = implode(',', $pids);
    if ($settings['steamAPI'] && $_SESSION['permissions']['view']['steam'] && !$settings['performance'] && $settings['vacTest']) {
        $api = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=" . $settings['steamAPI'] . "&steamids=" . $pids;
        $bans = get_object_vars(json_decode(file_get_contents($api)));
        $bans = $bans['players'];
        $steamPlayers = count($bans);
    } else {
        $steamPlayers = 0;
    }

    $result_of_query = $db_link->query($sql);
    ?>
    <h1 class="page-header">
        <?php echo $lang['players']; ?>
        <small><?php echo $lang['overview']; ?></small>
    </h1>
        <div class="content-panel">
            <table id="datatable"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?php echo $lang['name']; ?></th>
                    <th><?php echo $lang['playerID']; ?></th>
                    <th><?php echo $lang['cash']; ?></th>
                    <th><?php echo $lang['bank']; ?></th>
                    <th><?php echo $lang['cop']; ?></th>
                    <th><?php echo $lang['medic']; ?></th>
                    <th><?php echo $lang['admin']; ?></th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th><?php echo $lang['name']; ?></th>
                    <th><?php echo $lang['playerID']; ?></th>
                    <th><?php echo $lang['cash']; ?></th>
                    <th><?php echo $lang['bank']; ?></th>
                    <th><?php echo $lang['cop']; ?></th>
                    <th><?php echo $lang['medic']; ?></th>
                    <th><?php echo $lang['admin']; ?></th>
                </tr>
                </tfoot>

                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_of_query)) {
                    $playersID = $row["playerid"];
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $playersID . "</td>";
                    echo "<td>" . $row["cash"] . "</td>";
                    echo "<td>" . $row["bankacc"] . "</td>";
                    echo "<td>" . $row["coplevel"] . "</td>";
                    echo "<td>" . $row["mediclevel"] . "</td>";
                    echo "<td>" . $row["adminlevel"] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
                //include("views/templates/page.php");
                ?>
        </div>
<?php
} else echo '<h3>' . errorMessage(36, $lang) . '</h3>';