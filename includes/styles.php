<?php
// Common CSS & JS imports - ALL OFFLINE
?>
<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
    --color-teal: #0891b2;
    --color-teal-light: #06b6d4;
    --color-teal-dark: #0d9488;
}

body {
    background: linear-gradient(135deg, #f0f9fa 0%, #f5f3ff 100%);
    min-height: 100vh;
}

.navbar {
    background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%) !important;
    box-shadow: 0 2px 10px rgba(8, 145, 178, 0.2);
}

.btn-primary,
.btn-success,
.btn-info {
    background-color: #0891b2 !important;
    border-color: #0891b2 !important;
}

.btn-primary:hover,
.btn-success:hover,
.btn-info:hover {
    background-color: #0d9488 !important;
    border-color: #0d9488 !important;
}

.badge {
    font-weight: 500;
}

.table-hover tbody tr:hover {
    background-color: rgba(8, 145, 178, 0.05);
}

.card {
    border: 1px solid rgba(8, 145, 178, 0.1);
    box-shadow: 0 2px 15px rgba(8, 145, 178, 0.08);
    border-radius: 12px;
}

.text-primary {
    color: #0891b2 !important;
}

.bg-primary {
    background-color: #0891b2 !important;
}

.alert {
    border: none;
    border-left: 4px solid #0891b2;
}

.form-control:focus {
    border-color: #0891b2;
    box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.25);
}

.form-select:focus {
    border-color: #0891b2;
    box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.25);
}

/* Unicode Icons Fallback */
.icon-before::before {
    margin-right: 0.5rem;
    font-weight: bold;
}

.icon-dashboard::before {
    content: "📊 ";
}

.icon-students::before {
    content: "👥 ";
}

.icon-class::before {
    content: "🏫 ";
}

.icon-money::before {
    content: "💰 ";
}

.icon-add::before {
    content: "➕ ";
}

.icon-edit::before {
    content: "✏️ ";
}

.icon-delete::before {
    content: "🗑️ ";
}

.icon-back::before {
    content: "◀ ";
}

.icon-logout::before {
    content: "🚪 ";
}

.icon-list::before {
    content: "📋 ";
}

.icon-save::before {
    content: "💾 ";
}

.icon-cancel::before {
    content: "❌ ";
}

.icon-check::before {
    content: "✓ ";
}

.icon-warn::before {
    content: "⚠️ ";
}
</style>