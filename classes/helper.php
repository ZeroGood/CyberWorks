<?php

class helper
{
    //todo: change max system causing delay in page update
    public function pages($total_records, $pageNum = null, $items = null)
    {
        if ($total_records > 0) {
            if (!isset($items)) {
                if (isset($_GET['items'])) {
                    if (file_exists('config/settings.php')) {
                        $settings = include 'config/settings.php';
                    } else {
                        $settings['item'] = array(5, 10, 15, 25, 50);
                    }
                    if (in_array($_GET['items'], $settings['item']) && isset($_SESSION)) {
                        $query = new query();
                        $query->items($_SESSION['user_id'], $_GET['items']);
                        $_SESSION['items'] = intval($_GET['items']);
                    }
                }

                if (isset($_SESSION['items'])) {
                    $items = $_SESSION['items'];
                } else {
                    $items = 15;
                }
            }
            $total_pages = ceil($total_records / $items);

            if (!isset($pageNum)) {
                if (isset($_GET['page'])) {
                    $page['num'] = $_GET['page'];
                    if ($page['num'] < 1) {
                        $page['num'] = 1;
                    }
                } else {
                    $page['num'] = 1;
                }
            } else {
                $page['num'] = $pageNum;
            }

            if ($total_pages > 5) {
                $page['start'] = $page['num'] - 2;
                $page['end'] = $page['num'] + 2;
                if ($page['num'] < 4) $page['start'] = 1;
                if ($page['num'] < 2) $page['end'] += 1;
                if ($page['num'] < 3) $page['end'] += 1;

                if ($page['num'] > $total_pages - 2) {
                    $page['start'] -= 1;
                    $page['end'] -= 1;
                }
                if ($page['num'] > $total_pages - 1) {
                    $page['start'] -= 1;
                    $page['end'] -= 1;
                }
            } else {
                $page['start'] = 1;
                $page['end'] = $total_pages;
            }
            $page['total'] = $total_pages;
            return $page;
        } else {
            $page['end'] = 1;
            return $page;
        }
    }
}