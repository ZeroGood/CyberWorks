<?php
$pages = $helper->pages($total_records);

if ($pages['end'] > 1) { ?>
<center>
<nav>
  <ul class="pagination ">
    <li>
      <a href="?page=1" aria-label="First">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php
    for ($i = $pages['start']; $i <= $pages['end']; $i++) {
        echo '<li';
        if ($i == $pages['num']) echo  ' class="active"';
        echo '><a id="Page ' . $i . '" href="?page=' . $i . '">' . $i . '</a></li>';
    }; ?>

    <li>
      <a href="?page=<?php echo $pages['total'] ?>" aria-label="Last">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</center>
<?php } 