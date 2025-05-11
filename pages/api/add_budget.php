<?php
session_start();

include '../../config/config.php';
include '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$user_id = $_SESSION['user_id'];

$main_category_id = $_POST['main_category_id'] ?? null;
$sub_category_id = $_POST['sub_category_id'] ?? null;
$budget_amount = $_POST['budget_amount'] ?? null;
$start_month = $_POST['start_month'] ?? null;
$end_month = $_POST['end_month'] ?? null;
$accounts = $_POST['accounts'] ?? [];
$carry_over_under = isset($_POST['carry_over_under']) ? 1 : 0;
$carry_over_over = isset($_POST['carry_over_over']) ? 1 : 0;

$category_id = !empty($sub_category_id) ? $sub_category_id : $main_category_id;

if (empty($category_id) || empty($budget_amount) || empty($start_month) || empty($accounts)) {
    echo json_encode(['status' => 'error', 'message' => 'Champs obligatoires manquants']);
    exit;
}

$start_date = date('Y-m-01', strtotime($start_month));
$end_date = !empty($end_month) ? date('Y-m-t', strtotime($end_month)) : null;

$stmt = $conn->prepare("INSERT INTO budgets (user_id, category_id, budget_amount, period, start_date, end_date, carry_over_under, carry_over_over) VALUES (?, ?, ?, 'monthly', ?, ?, ?, ?)");
$stmt->execute([$user_id, $category_id, $budget_amount, $start_date, $end_date, $carry_over_under, $carry_over_over]);
$budget_id = $conn->lastInsertId();

foreach ($accounts as $account_id) {
    $stmt = $conn->prepare("INSERT INTO budget_accounts (budget_id, account_id) VALUES (?, ?)");
    $stmt->execute([$budget_id, $account_id]);
}

echo json_encode(['status' => 'success']);