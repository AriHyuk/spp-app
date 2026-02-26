<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>App SPP Universitas Pamulang</title>
    
    <!-- Base URL for Assets -->
    <?php $base_url = 'http://localhost/spp-app'; ?>
    
    <link href="<?= $base_url ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --color-teal: #0891b2;
        --color-teal-dark: #0e7490;
        --sidebar-width: 260px;
    }

    body {
        background-color: #f3f4f6;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Sidebar Styling */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #0891b2 0%, #06b6d4 100%);
        color: white;
        z-index: 1000;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .sidebar-brand {
        padding: 1.5rem;
        font-size: 1.5rem;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-panel {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
    }

    .nav-link.sidebar-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 12px 24px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        border-left: 4px solid transparent;
        text-decoration: none;
    }

    .nav-link.sidebar-link:hover,
    .nav-link.sidebar-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        border-left-color: white;
    }

    .nav-link.sidebar-link i {
        margin-right: 12px;
        font-size: 1.1rem;
    }

    /* Main Content Styling */
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 2rem;
        width: calc(100% - var(--sidebar-width));
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar.active {
            margin-left: 0;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }
    
    /* Utility Classes */
    .stat-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
        background: white;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    </style>
</head>
<body>