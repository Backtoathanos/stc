<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../MCU/db.php";

$search = mysqli_real_escape_string($con, $_GET['search'] ?? '');
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10);
$location_stc = $_GET['location'] ?? '';
$location_esc = mysqli_real_escape_string($con, $location_stc);
$offset = ($page - 1) * $limit;

// One-time aggregate maps (avoids per-product query loops)
$maps = loadInventoryAggregateMaps($con, $location_esc, $location_stc);

$cquery = "SELECT DISTINCT P.stc_product_id, P.stc_product_name, S.stc_sub_cat_name, C.stc_cat_name, P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image, P.stc_product_gst, P.stc_product_sale_percentage, B.stc_brand_title
FROM stc_product P
INNER JOIN stc_sub_category S ON P.stc_product_sub_cat_id = S.stc_sub_cat_id
LEFT JOIN stc_category C ON C.stc_cat_id = P.stc_product_cat_id
LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id
WHERE P.stc_product_avail = 1
AND EXISTS (
    SELECT 1 FROM stc_purchase_product_adhoc spa
    WHERE spa.stc_purchase_product_adhoc_productid = P.stc_product_id
    AND spa.stc_purchase_product_adhoc_status = 1
)";

if ($search) {
    $cquery .= " AND (P.stc_product_id='$search' OR P.stc_product_name LIKE '%$search%' OR P.stc_product_desc LIKE '%$search%')";
}

$result = mysqli_query($con, $cquery . " ORDER BY P.stc_product_name ASC");

$inStockRows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = (int)$row['stc_product_id'];

        $row['stc_product_name'] = ($row['stc_sub_cat_name'] != "OTHERS" ? $row['stc_sub_cat_name'] . " " : "")
            . $row['stc_product_name'] . ' ' . $row['stc_brand_title'];
        $row['stc_product_name'] = trim($row['stc_product_name'], ' ');

        $qtyNum = computeRemainingQtyFromMaps($product_id, $location_stc, $maps);
        $row['stc_item_inventory_pd_qty'] = number_format($qtyNum, 2, '.', '');

        $row = applyRackAndRatesFromMaps($row, $product_id, $location_stc, $maps);

        if ($qtyNum > 0) {
            $inStockRows[] = $row;
        }
    }
}

$totalRow = count($inStockRows);
$data = array_slice($inStockRows, $offset, $limit);

echo json_encode(['records' => $data, 'total' => $totalRow, 'page' => $page, 'limit' => $limit]);

/**
 * Runs a small fixed set of GROUP BY queries once per request.
 */
function loadInventoryAggregateMaps($con, $location_esc, $location_raw)
{
    $maps = [
        'adhoc_total_qty' => [],
        'gld_root' => [],
        'gld_by_prod_loc' => [],
        'direct_qty' => [],
        'shop_qty' => [],
        'adhoc_latest_rate' => [],
        'root_rack' => [],
        'grn_latest_rate' => [],
        'branch_rack' => [],
    ];

    // 1) Total purchased qty (status = 1) per product
    $sql = "SELECT stc_purchase_product_adhoc_productid AS pid, SUM(stc_purchase_product_adhoc_qty) AS q
            FROM stc_purchase_product_adhoc
            WHERE stc_purchase_product_adhoc_status = 1
            GROUP BY stc_purchase_product_adhoc_productid";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['adhoc_total_qty'][(int)$r['pid']] = (float)($r['q'] ?? 0);
        }
    }

    // 2) GLD challan totals — Root (all challans per product)
    $sql = "SELECT product_id AS pid, SUM(qty) AS q FROM gld_challan GROUP BY product_id";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['gld_root'][(int)$r['pid']] = (float)($r['q'] ?? 0);
        }
    }

    // 3) GLD challan per product + issuing user location (branches)
    $sql = "SELECT gc.product_id AS pid, tu.stc_trading_user_location AS loc, SUM(gc.qty) AS q
            FROM gld_challan gc
            INNER JOIN stc_trading_user tu ON tu.stc_trading_user_id = gc.created_by
            GROUP BY gc.product_id, tu.stc_trading_user_location";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $key = (int)$r['pid'] . '|' . $r['loc'];
            $maps['gld_by_prod_loc'][$key] = (float)($r['q'] ?? 0);
        }
    }

    // 4) Direct requisition off adhoc (status = 1)
    $sql = "SELECT spa.stc_purchase_product_adhoc_productid AS pid,
                SUM(srec.stc_cust_super_requisition_list_items_rec_recqty) AS q
            FROM stc_purchase_product_adhoc spa
            JOIN stc_cust_super_requisition_list_items_rec srec
                ON srec.stc_cust_super_requisition_list_items_rec_list_poaid = spa.stc_purchase_product_adhoc_id
            WHERE spa.stc_purchase_product_adhoc_status = 1
            GROUP BY spa.stc_purchase_product_adhoc_productid";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['direct_qty'][(int)$r['pid']] = (float)($r['q'] ?? 0);
        }
    }

    // 5) Shop qty per product + branch (prefer branch, else shopname)
    $sql = "SELECT spa.stc_purchase_product_adhoc_productid AS pid,
                   COALESCE(NULLIF(TRIM(S.branch), ''), S.shopname) AS shopname,
                   SUM(S.qty) AS q
            FROM stc_purchase_product_adhoc spa
            JOIN stc_shop S ON S.adhoc_id = spa.stc_purchase_product_adhoc_id
            WHERE spa.stc_purchase_product_adhoc_status = 1
            GROUP BY spa.stc_purchase_product_adhoc_productid, COALESCE(NULLIF(TRIM(S.branch), ''), S.shopname)";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $key = (int)$r['pid'] . '|' . $r['shopname'];
            $maps['shop_qty'][$key] = (float)($r['q'] ?? 0);
        }
    }

    // 6) Latest adhoc rate per product (any status — matches original ORDER BY id DESC LIMIT 1)
    $sql = "SELECT a.stc_purchase_product_adhoc_productid AS pid, a.stc_purchase_product_adhoc_rate AS adhoc_rate
            FROM stc_purchase_product_adhoc a
            INNER JOIN (
                SELECT stc_purchase_product_adhoc_productid AS pid2, MAX(stc_purchase_product_adhoc_id) AS mid
                FROM stc_purchase_product_adhoc
                GROUP BY stc_purchase_product_adhoc_productid
            ) m ON m.mid = a.stc_purchase_product_adhoc_id
                AND m.pid2 = a.stc_purchase_product_adhoc_productid";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['adhoc_latest_rate'][(int)$r['pid']] = $r['adhoc_rate'];
        }
    }

    // 7) Root rack from latest status=1 adhoc line
    $sql = "SELECT a.stc_purchase_product_adhoc_productid AS pid, rack.stc_rack_name AS rack_name
            FROM stc_purchase_product_adhoc a
            LEFT JOIN stc_rack rack ON rack.stc_rack_id = a.stc_purchase_product_adhoc_rackid
            INNER JOIN (
                SELECT stc_purchase_product_adhoc_productid AS pid2, MAX(stc_purchase_product_adhoc_id) AS mid
                FROM stc_purchase_product_adhoc
                WHERE stc_purchase_product_adhoc_status = 1
                GROUP BY stc_purchase_product_adhoc_productid
            ) m ON m.mid = a.stc_purchase_product_adhoc_id
                AND m.pid2 = a.stc_purchase_product_adhoc_productid";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['root_rack'][(int)$r['pid']] = $r['rack_name'] ?? '';
        }
    }

    // 8) Latest GRN rate per product (fallback when no adhoc rate row used)
    $sql = "SELECT g.stc_product_grn_items_product_id AS pid, g.stc_product_grn_items_rate AS grn_rate
            FROM stc_product_grn_items g
            INNER JOIN (
                SELECT stc_product_grn_items_product_id AS pid2, MAX(stc_product_grn_items_id) AS mid
                FROM stc_product_grn_items
                GROUP BY stc_product_grn_items_product_id
            ) m ON m.mid = g.stc_product_grn_items_id
                AND m.pid2 = g.stc_product_grn_items_product_id";
    $res = mysqli_query($con, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $maps['grn_latest_rate'][(int)$r['pid']] = $r['grn_rate'];
        }
    }

    // 9) Branch rack: latest stc_shop row per product for this shop
    if ($location_raw !== 'Root' && $location_esc !== '') {
        $sql = "SELECT spa.stc_purchase_product_adhoc_productid AS pid, r.stc_rack_name AS rack_name
                FROM stc_shop S
                JOIN stc_purchase_product_adhoc spa ON spa.stc_purchase_product_adhoc_id = S.adhoc_id
                LEFT JOIN stc_rack r ON r.stc_rack_id = S.rack_id
                JOIN (
                    SELECT spa2.stc_purchase_product_adhoc_productid AS pid2, MAX(S2.id) AS max_sid
                    FROM stc_shop S2
                    JOIN stc_purchase_product_adhoc spa2 ON spa2.stc_purchase_product_adhoc_id = S2.adhoc_id
                    WHERE COALESCE(NULLIF(TRIM(S2.branch), ''), S2.shopname) = '$location_esc' AND spa2.stc_purchase_product_adhoc_status = 1
                    GROUP BY spa2.stc_purchase_product_adhoc_productid
                ) t ON t.pid2 = spa.stc_purchase_product_adhoc_productid AND t.max_sid = S.id
                WHERE COALESCE(NULLIF(TRIM(S.branch), ''), S.shopname) = '$location_esc' AND spa.stc_purchase_product_adhoc_status = 1";
        $res = mysqli_query($con, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $maps['branch_rack'][(int)$r['pid']] = $r['rack_name'] ?? '';
            }
        }
    }

    return $maps;
}

function computeRemainingQtyFromMaps($product_id, $location, array $maps)
{
    if ($location === 'Root') {
        $total = $maps['adhoc_total_qty'][$product_id] ?? 0;
        $gld = $maps['gld_root'][$product_id] ?? 0;
        $direct = $maps['direct_qty'][$product_id] ?? 0;
        return max(0, $total - $gld - $direct);
    }

    $key = $product_id . '|' . $location;
    $locQty = $maps['shop_qty'][$key] ?? 0;
    if ($locQty <= 0) {
        return 0;
    }
    $dispatched = $maps['gld_by_prod_loc'][$key] ?? 0;
    return max(0, $locQty - $dispatched);
}

function applyRackAndRatesFromMaps(array $row, $product_id, $location, array $maps)
{
    if ($location === 'Root') {
        $row['stc_rack_name'] = $maps['root_rack'][$product_id] ?? '';
    } else {
        $row['stc_rack_name'] = $maps['branch_rack'][$product_id] ?? '';
    }

    $pct = floatval($row['stc_product_sale_percentage'] ?? 0);
    $adhocRate = $maps['adhoc_latest_rate'][$product_id] ?? null;

    if ($adhocRate !== null && $adhocRate !== '') {
        $base = floatval($adhocRate);
        $row['rate_including_gst'] = number_format($base, 2);
        $row['rate_including_percentage'] = number_format(round($base * (1 + $pct / 100), 2), 2);
    } else {
        $grn = $maps['grn_latest_rate'][$product_id] ?? null;
        if ($grn !== null && $grn !== '') {
            $base = floatval($grn);
            $row['rate_including_gst'] = number_format($base, 2);
            $row['rate_including_percentage'] = number_format(round($base * (1 + $pct / 100), 2), 2);
        } else {
            $row['rate_including_gst'] = '0.00';
            $row['rate_including_percentage'] = '0.00';
        }
    }

    return $row;
}
