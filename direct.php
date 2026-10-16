<?php
session_start();

/* Handle logout */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: home.html");
    exit();
}

/* Require an authenticated user */
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

/* Admin-only access */
$user = $_SESSION['user'];
$role = $_SESSION['role'];

if (strtolower(trim($role)) !== "admin") {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Nectar &amp; Ambrosia</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        @font-face {
            font-family: "AUGUSTUS";
            src: url("fonts/AUGUSTUS.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "AUGUSTUS", serif;
            color: #1f3b5c;
            background: url("bg/mainbg.jpg") no-repeat center center fixed;
            background-size: cover;
        }

        button {
            font-family: inherit;
        }

        /* Narrow dashboard */
        .dashboard-card {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.97);
            border: 2px solid #d4af37;
            border-radius: 18px;
            overflow: hidden;
            padding: 26px
            box-shadow: 0 10px 28px rgba(31, 59, 92, 0.16);
        }

        /* Logo */
        .card-header {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8px 20px 4px;
            border: none;
            background: transparent;
        }

        .brand-logo {
            display: block;
            width: 340px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Menu area */
        .card-content {
            padding: 8px 16px 16px;
        }

        .section-label {
            margin: 0 0 10px;
            font-family: inherit;
            font-size: 12px;
            font-weight: normal;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-list form {
            width: 100%;
            margin: 0;
        }

        /* Navigation buttons */
        .menu-card {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 58px;
            padding: 16px 18px;
            background: #ffffff;
            margin-bottom: 14px;
            border: 1px solid #dce6ee;
            border-left: 4px solid #4cabdc;
            border-radius: 10px;
            color: #1f3b5c;
            text-decoration: none;
            font-family: inherit;
            font-size: inherit;
            text-align: left;
            cursor: pointer;
            transition: background 0.2s ease,
                        border-color 0.2s ease,
                        transform 0.2s ease;
        }

        .menu-card:hover {
            background: #f2f9fd;
            border-color: #d4af37;
            transform: translateY(-1px);
        }

        .menu-card:focus-visible {
            outline: 3px solid #4cabdc;
            outline-offset: 2px;
        }

        .menu-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #e8f5fc;
            color: #2f6485;
            font-size: 16px;
        }

        .menu-text {
            flex: 1;
            min-width: 0;
        }


        .menu-title {
            display: block;
            font-family: "AUGUSTUS", serif;
            font-size: 16px;  /* Slightly bigger and bold */
            font-weight: bold;
            color: #1f3b5c;
        }


        .menu-arrow {
            flex-shrink: 0;
            color: #9aabc0;
            font-size: 12px;
        }

        /* Logout */
        .logout-card {
            border-left-color: #c87979;
        }

        .logout-card .menu-icon {
            background: #fbefef;
            color: #a34848;
        }

        .logout-card .menu-title {
            color: #8f4141;
        }

        .logout-card:hover {
            background: #fff7f7;
            border-color: #d9a3a3;
        }

        /* Footer */
        .card-footer {
            padding: 9px 8px;
            text-align: center;
            font-family: inherit;
            font-size: 10px;
            color: #8794a3;
            border-top: 1px solid #edf0f3;
            background: #ffffff;
        }

        /* Responsive */
        @media (max-width: 460px) {
            body {
                padding: 12px;
                background-attachment: scroll;
            }

            .dashboard-card {
                max-width: 100%;
            }

            .brand-logo {
                width: 300px;
            }

            .card-content {
                padding: 8px 12px 14px;
            }
        }

        @media (max-height: 600px) {
            body {
                align-items: flex-start;
                padding-top: 8px;
                padding-bottom: 8px;
            }

            .brand-logo {
                width: 240px;
            }

            .card-header {
                padding: 4px 12px;
            }

            .card-content {
                padding-top: 6px;
                padding-bottom: 10px;
            }

            .menu-list {
                gap: 6px;
            }

            .menu-card {
                min-height: 48px;
                padding: 6px 10px;
            }

            .card-footer {
                padding: 7px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <main class="dashboard-card">

        <header class="card-header">
            <img
                src="bg/LOGOS.png"
                class="brand-logo"
                alt="Nectar &amp; Ambrosia Logo"
            >
        </header>

        <section class="card-content">
            <h2 class="section-label"></h2>

            <nav class="menu-list" aria-label="Admin menu">

                <a href="order.php" class="menu-card">
                    <span class="menu-icon">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </span>
                    <span class="menu-text">
                        <span class="menu-title">Take an Order</span>
                    </span>
                    <i class="fa-solid fa-chevron-right menu-arrow"></i>
                </a>

                <a href="reports.php" class="menu-card">
                    <span class="menu-icon">
                        <i class="fa-solid fa-chart-column"></i>
                    </span>
                    <span class="menu-text">
                        <span class="menu-title">View Reports</span>
                    </span>
                    <i class="fa-solid fa-chevron-right menu-arrow"></i>
                </a>

                <a href="update.php" class="menu-card">
                    <span class="menu-icon">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </span>
                    <span class="menu-text">
                        <span class="menu-title">Update Menu</span>
                    </span>
                    <i class="fa-solid fa-chevron-right menu-arrow"></i>
                </a>

                <form method="POST" action="direct.php">
                    <button
                        type="submit"
                        name="logout"
                        value="1"
                        class="menu-card logout-card"
                    >
                        <span class="menu-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </span>
                        <span class="menu-text">
                            <span class="menu-title">Logout</span>
                        </span>
                        <i class="fa-solid fa-chevron-right menu-arrow"></i>
                    </button>
                </form>

            </nav>
        </section>

        <footer class="card-footer">
            Nectar &amp; Ambrosia &middot; Admin Dashboard
        </footer>

    </main>
</body>
</html>
