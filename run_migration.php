<?php
/**
 * Safe Migration Runner - Budget Approval + Finance Flow
 * Run via browser: http://localhost/kanisa/church-cms/run_migration.php
 * DELETE this file after running!
 */

require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/core/Database.php';

$pdo = Database::connect();
$results = [];

function colExists(PDO $pdo, string $table, string $col): bool {
    $s = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND COLUMN_NAME=?");
    $s->execute([$table, $col]);
    return (int)$s->fetchColumn() > 0;
}
function tblExists(PDO $pdo, string $t): bool {
    $s = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?");
    $s->execute([$t]);
    return (int)$s->fetchColumn() > 0;
}

// 1. events.budget_status
if (!colExists($pdo, 'events', 'budget_status')) {
    try {
        $pdo->exec("ALTER TABLE `events` ADD COLUMN `budget_status` ENUM('draft','pending_approval','approved','rejected') NOT NULL DEFAULT 'draft' AFTER `budget_total`");
        $results[] = "✅ Added events.budget_status";
    } catch (Exception $e) { $results[] = "❌ events.budget_status: ".$e->getMessage(); }
} else { $results[] = "⏭️ events.budget_status exists"; }

// 2. events.budget_approved_by + budget_approved_at
if (!colExists($pdo, 'events', 'budget_approved_by')) {
    try {
        $pdo->exec("ALTER TABLE `events` ADD COLUMN `budget_approved_by` BIGINT UNSIGNED NULL AFTER `budget_status`, ADD COLUMN `budget_approved_at` DATETIME NULL AFTER `budget_approved_by`");
        $results[] = "✅ Added events.budget_approved_by, budget_approved_at";
    } catch (Exception $e) { $results[] = "❌ events.budget_approved_by: ".$e->getMessage(); }
} else { $results[] = "⏭️ events.budget_approved_by exists"; }

// 3. finance_entries.approval_status
if (!colExists($pdo, 'finance_entries', 'approval_status')) {
    try {
        $pdo->exec("ALTER TABLE finance_entries ADD COLUMN `approval_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'approved' AFTER `approved_at`");
        $results[] = "✅ Added finance_entries.approval_status";
    } catch (Exception $e) { $results[] = "❌ finance_entries.approval_status: ".$e->getMessage(); }
} else { $results[] = "⏭️ finance_entries.approval_status exists"; }

// 4. finance_entries.rejection_count
if (!colExists($pdo, 'finance_entries', 'rejection_count')) {
    try {
        $pdo->exec("ALTER TABLE finance_entries ADD COLUMN `rejection_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `approval_status`");
        $results[] = "✅ Added finance_entries.rejection_count";
    } catch (Exception $e) { $results[] = "❌ finance_entries.rejection_count: ".$e->getMessage(); }
} else { $results[] = "⏭️ finance_entries.rejection_count exists"; }

// 5. event_finance_links
if (!tblExists($pdo, 'event_finance_links')) {
    try {
        $pdo->exec("CREATE TABLE event_finance_links (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            event_id BIGINT UNSIGNED NOT NULL,
            finance_entry_id BIGINT UNSIGNED NOT NULL,
            relation_type ENUM('income','expense') NOT NULL DEFAULT 'expense',
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_efl_evt FOREIGN KEY (event_id) REFERENCES `events`(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_efl_fe FOREIGN KEY (finance_entry_id) REFERENCES finance_entries(id) ON UPDATE CASCADE ON DELETE CASCADE,
            UNIQUE KEY uq_efl (event_id, finance_entry_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $results[] = "✅ Created event_finance_links table";
    } catch (Exception $e) { $results[] = "❌ event_finance_links: ".$e->getMessage(); }
} else {
    if (!colExists($pdo, 'event_finance_links', 'relation_type')) {
        try {
            $pdo->exec("ALTER TABLE event_finance_links ADD COLUMN `relation_type` ENUM('income','expense') NOT NULL DEFAULT 'expense' AFTER `finance_entry_id`");
            $results[] = "✅ Added event_finance_links.relation_type";
        } catch (Exception $e) { $results[] = "❌ event_finance_links.relation_type: ".$e->getMessage(); }
    } else { $results[] = "⏭️ event_finance_links OK"; }
}

// 6. Check pledges + department_budgets
$results[] = tblExists($pdo, 'pledges') ? "⏭️ pledges OK" : "⚠️ pledges missing - run finance_module_upgrade";
$results[] = tblExists($pdo, 'department_budgets') ? "⏭️ department_budgets OK" : "⚠️ department_budgets missing";

echo "<!DOCTYPE html><html><head><title>Migration</title>
<style>body{font-family:system-ui;max-width:700px;margin:40px auto;padding:20px;background:#f8fafc}
h1{color:#1e3a8a}pre{background:#1e293b;color:#e2e8f0;padding:20px;border-radius:12px;line-height:2}</style></head>
<body><h1>Budget Flow Migration</h1><pre>\n";
foreach ($results as $r) echo htmlspecialchars($r)."\n";
echo "</pre><p style='color:#ef4444;font-weight:bold;margin-top:20px'>⚠️ DELETE this file after running!</p></body></html>";
