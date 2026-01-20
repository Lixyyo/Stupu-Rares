<?php
require_once __DIR__ . '/db.php';

function require_login(): void {
  if (session_status() === PHP_SESSION_NONE) session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
  }
}

function get_cart(): array {
  if (session_status() === PHP_SESSION_NONE) session_start();
  return $_SESSION['cart'] ?? [];
}

function set_cart(array $cart): void {
  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['cart'] = $cart;
}

/**
 * Returns products keyed by id_produs.
 */
function fetch_products_by_ids(PDO $pdo, array $ids): array {
  if (!$ids) return [];
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT id_produs, nume_produs, pret, categorie, cantitatep FROM Produse WHERE id_produs IN ($placeholders)");
  $stmt->execute(array_values($ids));
  $out = [];
  foreach ($stmt->fetchAll() as $row) {
    $out[(int)$row['id_produs']] = $row;
  }
  return $out;
}

/**
 * Tries to compute stock for a product.
 * If Magazie has rows, use SUM(cantitatem). Otherwise fall back to Produse.cantitatep.
 */
function fetch_stock(PDO $pdo, int $productId): int {
  $stmt = $pdo->prepare("SELECT COALESCE(SUM(cantitatem), 0) AS stoc FROM Magazie WHERE id_produs = ?");
  $stmt->execute([$productId]);
  $stoc = (int)($stmt->fetch()['stoc'] ?? 0);
  if ($stoc > 0) return $stoc;

  $stmt2 = $pdo->prepare("SELECT COALESCE(cantitatep, 0) AS stoc FROM Produse WHERE id_produs = ?");
  $stmt2->execute([$productId]);
  return (int)($stmt2->fetch()['stoc'] ?? 0);
}

/**
 * Decreases stock from Magazie rows for given product.
 * If oras is provided, it prioritizes that city.
 * Throws Exception if not enough stock.
 */
function decrease_stock(PDO $pdo, int $productId, int $qty, ?string $oras = null): void {
  if ($qty <= 0) return;

  // Lock rows for this product (and city first) so checkout is safe.
  $sql = "SELECT id_magazie, cantitatem, oras_m
          FROM Magazie
          WHERE id_produs = ?";
  $params = [$productId];
  if ($oras !== null && $oras !== '') {
    $sql .= " ORDER BY (oras_m = ?) DESC, cantitatem DESC";
    $params[] = $oras;
  } else {
    $sql .= " ORDER BY cantitatem DESC";
  }
  $sql .= " FOR UPDATE";

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $rows = $stmt->fetchAll();

  $available = 0;
  foreach ($rows as $r) $available += (int)($r['cantitatem'] ?? 0);
  if ($available < $qty) {
    throw new Exception("Stoc insuficient pentru produsul ID $productId (stoc=$available, cerut=$qty)");
  }

  $remaining = $qty;
  foreach ($rows as $r) {
    if ($remaining <= 0) break;
    $idMag = (int)$r['id_magazie'];
    $have  = (int)($r['cantitatem'] ?? 0);
    if ($have <= 0) continue;

    $take = min($have, $remaining);
    $upd = $pdo->prepare("UPDATE Magazie SET cantitatem = cantitatem - ? WHERE id_magazie = ?");
    $upd->execute([$take, $idMag]);
    $remaining -= $take;
  }
}

function generate_order_group_id(): int {
  // millisecond timestamp + random suffix (fits in BIGINT)
  return (int)(microtime(true) * 1000) * 1000 + random_int(0, 999);
}
