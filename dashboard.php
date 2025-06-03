<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command HQ - Educational Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Command HQ</h1>
            </div>
            
            <nav class="sidebar-nav">
                <button class="nav-item active" data-page="dashboard">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Dashboard
                </button>
                
                <button class="nav-item" data-page="analytics">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    Analytics
                </button>
                
                <button class="nav-item" data-page="customers">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A2.996 2.996 0 0 0 16.5 6c-.76 0-1.43.35-1.88.88L11 12l-3.5-3.5c-.45-.45-1.06-.73-1.74-.73-.76 0-1.43.35-1.88.88L1 15.63V20h2v-2.5l2-6.5 2.5 2.5L5 18h3l2.5-2.5L13 18h3z"/>
                    </svg>
                    Students
                </button>
                
                <button class="nav-item" data-page="products">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Courses
                </button>
                
                <button class="nav-item" data-page="payments">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                    Payments
                </button>
                
                <button class="nav-item" data-page="calendar">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    Calendar
                </button>
                
                <button class="nav-item" data-page="messages">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    Messages
                </button>
                
                <button class="nav-item" data-page="settings">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.82,11.69,4.82,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
                    </svg>
                    Settings
                </button>
                
                <button class="nav-item" data-page="support">
                    <svg class="nav-icon" viewBox="0 0 24 24">
                        <path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>
                    </svg>
                    Help & Support
                </button>
            </nav>
            
            <!-- <div class="user-info">
                <div class="user-name">John Doe</div>
                <div class="user-email">john@example.com</div>
            </div>
             -->
            <!-- <div class="upgrade-section">
                <h3>Upgrade to Pro</h3>
                <p>Unlock all features and get unlimited access to everything.</p>
                <button class="upgrade-btn">Upgrade</button>
            </div> -->
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header class="header">
                <h2>Dashboard</h2>
                <div class="header-controls">
                    <input type="text" class="search-box" placeholder="Search...">
                    <button class="theme-toggle">🌙</button>
                </div>
            </header>

            <div class="content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Average Score</span>
                            <svg class="stat-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                            </svg>
                        </div>
                        <div class="stat-value">86%</div>
                        <div class="stat-change positive">
                            ↑ 4.2% compared to last semester
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">New Enrollments</span>
                            <svg class="stat-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div class="stat-value">+1,250</div>
                        <div class="stat-change positive">
                            ↑ 12.5% compared to last semester
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Completed Courses</span>
                            <svg class="stat-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div class="stat-value">3,402</div>
                        <div class="stat-change positive">
                            ↑ 7.8% compared to last semester
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Active Students</span>
                            <svg class="stat-icon" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div class="stat-value">5,876</div>
                        <div class="stat-change positive">
                            ↑ 5.3% currently active
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="chart-section">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Overview</h3>
                            <div class="chart-tabs">
                                <button class="chart-tab active" data-chart="revenue">Revenue</button>
                                <button class="chart-tab" data-chart="devices">Devices</button>
                            </div>
                        </div>
                        <div style="position: relative; height: 300px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <div style="margin-top: 20px;">
                            <h4>Revenue Breakdown</h4>
                            <div style="position: relative; height: 200px; margin-top: 10px;">
                                <canvas id="doughnutChart"></canvas>
                            </div>
                            <div style="display: flex; gap: 20px; margin-top: 15px; font-size: 14px;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div style="width: 12px; height: 12px; background: #4285f4; border-radius: 50%;"></div>
                                    Science
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div style="width: 12px; height: 12px; background: #00d4aa; border-radius: 50%;"></div>
                                    Math
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div style="width: 12px; height: 12px; background: #ff6b6b; border-radius: 50%;"></div>
                                    Programming
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <div style="width: 12px; height: 12px; background: #ffa726; border-radius: 50%;"></div>
                                    Languages
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="activity-section">
                        <h3 class="activity-header">Recent Activity</h3>
                        <div id="activityList">
                            <div class="activity-item">
                                <div class="activity-avatar">AB</div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <strong>Alice Brown</strong> completed the course <strong>Introduction to Programming</strong>
                                    </div>
                                    <div class="activity-time">May 15, 2025</div>
                                </div>
                                <div class="activity-timestamp">03:45 PM</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-avatar">JL</div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <strong>James Lee</strong> submitted assignment for <strong>Data Structures</strong>
                                    </div>
                                    <div class="activity-time">May 15, 2025</div>
                                </div>
                                <div class="activity-timestamp">01:15 PM</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-avatar">ST</div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <strong>Sophia Turner</strong> started new course <strong>Web Development Basics</strong>
                                    </div>
                                    <div class="activity-time">May 14, 2025</div>
                                </div>
                                <div class="activity-timestamp">11:00 AM</div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-avatar">EW</div>
                                <div class="activity-content">
                                    <div class="activity-text">
                                        <strong>Ethan Walker</strong> achieved top score in <strong>Mathematics Quiz</strong>
                                    </div>
                                    <div class="activity-time">May 13, 2025</div>
                                </div>
                                <div class="activity-timestamp">09:25 AM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <script src="js/script.js"></script>
</body>
</html>

