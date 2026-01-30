<?php
?>
<nav class="navbar navbar-expand-lg navbar-dark"
    style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="../index.php">
            <i class="bi bi-wallet2 me-2"></i> SPP Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="../index.php" class="nav-link text-white">
                        <i class="bi bi-house me-1"></i> Dashboard
                    </a>
                </li>
                <?php if (isset($_SESSION['id_petugas'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../petugas/index.php">
                                <i class="bi bi-people me-2"></i> Kelola Petugas
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="../logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>