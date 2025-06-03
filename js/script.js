
        // Initialize charts
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');

        // Revenue chart data
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [65000, 59000, 80000, 81000, 56000, 85000],
                    borderColor: '#4285f4',
                    backgroundColor: 'rgba(66, 133, 244, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#333'
                        },
                        ticks: {
                            color: '#888'
                        }
                    },
                    x: {
                        grid: {
                            color: '#333'
                        },
                        ticks: {
                            color: '#888'
                        }
                    }
                }
            }
        });

        // Doughnut chart
        const doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Science', 'Math', 'Programming', 'Languages'],
                datasets: [{
                    data: [35, 25, 25, 15],
                    backgroundColor: ['#4285f4', '#00d4aa', '#ff6b6b', '#ffa726'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Navigation functionality
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                const page = this.dataset.page;
                document.querySelector('.header h2').textContent = page.charAt(0).toUpperCase() + page.slice(1);
            });
        });

        // Chart tabs functionality
        document.querySelectorAll('.chart-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Here you could switch between different chart data
                if (this.dataset.chart === 'devices') {
                    // Update chart with device data
                    console.log('Switching to devices chart');
                } else {
                    // Show revenue data
                    console.log('Switching to revenue chart');
                }
            });
        });

        // Theme toggle
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('light-theme');
            this.textContent = document.body.classList.contains('light-theme') ? '☀️' : '🌙';
        });

        // Search functionality
        document.querySelector('.search-box').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // Implement search logic here
        });

        // Simulate real-time updates
        function updateStats() {
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const currentValue = parseInt(stat.textContent.replace(/[^\d]/g, ''));
                const change = Math.floor(Math.random() * 10) - 5;
                const newValue = Math.max(0, currentValue + change);
                
                if (stat.textContent.includes('%')) {
                    stat.textContent = newValue + '%';
                } else if (stat.textContent.includes('+')) {
                    stat.textContent = '+' + newValue.toLocaleString();
                } else {
                    stat.textContent = newValue.toLocaleString();
                }
            });
        }

        // Add new activity items
        function addActivity() {
            const activities = [
                {
                    name: "Maria Garcia",
                    action: "completed quiz in",
                    subject: "Calculus Advanced",
                    time: "Just now",
                    initials: "MG"
                },
                {
                    name: "David Chen",
                    action: "enrolled in",
                    subject: "Machine Learning Basics",
                    time: "2 minutes ago",
                    initials: "DC"
                },
                {
                    name: "Sarah Johnson",
                    action: "submitted project for",
                    subject: "Physics Lab",
                    time: "5 minutes ago",
                    initials: "SJ"
                }
            ];

            const randomActivity = activities[Math.floor(Math.random() * activities.length)];
            const activityList = document.getElementById('activityList');
            
            const newActivity = document.createElement('div');
            newActivity.className = 'activity-item';
            newActivity.style.opacity = '0';
            newActivity.style.transform = 'translateY(-20px)';
            
            newActivity.innerHTML = `
                <div class="activity-avatar">${randomActivity.initials}</div>
                <div class="activity-content">
                    <div class="activity-text">
                        <strong>${randomActivity.name}</strong> ${randomActivity.action} <strong>${randomActivity.subject}</strong>
                    </div>
                    <div class="activity-time">${new Date().toLocaleDateString()}</div>
                </div>
                <div class="activity-timestamp">${randomActivity.time}</div>
            `;
            
            activityList.insertBefore(newActivity, activityList.firstChild);
            
            // Animate in
            setTimeout(() => {
                newActivity.style.transition = 'all 0.3s ease';
                newActivity.style.opacity = '1';
                newActivity.style.transform = 'translateY(0)';
            }, 100);
            
            // Remove oldest activity if more than 5
            const activities_items = activityList.querySelectorAll('.activity-item');
            if (activities_items.length > 5) {
                activities_items[activities_items.length - 1].remove();
            }
        }

        // Upgrade button functionality
        document.querySelector('.upgrade-btn').addEventListener('click', function() {
            alert('Upgrade feature coming soon! 🚀');
        });

        // Add hover effects to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 30px rgba(66, 133, 244, 0.2)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });

        // Simulate live data updates every 10 seconds
        setInterval(() => {
            updateStats();
            
            // Randomly add new activity
            if (Math.random() > 0.7) {
                addActivity();
            }
            
            // Update chart data
            const newData = revenueChart.data.datasets[0].data.map(value => {
                const change = Math.floor(Math.random() * 10000) - 5000;
                return Math.max(10000, value + change);
            });
            
            revenueChart.data.datasets[0].data = newData;
            revenueChart.update('none');
            
        }, 10000);

        // Responsive sidebar toggle for mobile
        function createMobileToggle() {
            if (window.innerWidth <= 768) {
                const header = document.querySelector('.header');
                const toggleBtn = document.createElement('button');
                toggleBtn.innerHTML = '☰';
                toggleBtn.style.cssText = `
                    background: none;
                    border: none;
                    color: white;
                    font-size: 20px;
                    cursor: pointer;
                    margin-right: 15px;
                `;
                
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ? 'translateX(-100%)' : 'translateX(0px)';
                });
                
                header.insertBefore(toggleBtn, header.firstChild);
            }
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                createMobileToggle();
            }
        });

        // Initial mobile check
        createMobileToggle();

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'k':
                        e.preventDefault();
                        document.querySelector('.search-box').focus();
                        break;
                    case '1':
                        e.preventDefault();
                        document.querySelector('[data-page="dashboard"]').click();
                        break;
                    case '2':
                        e.preventDefault();
                        document.querySelector('[data-page="analytics"]').click();
                        break;
                }
            }
        });

        // Add tooltips for better UX
        function addTooltips() {
            const tooltipElements = [
                { selector: '.theme-toggle', text: 'Toggle dark/light mode' },
                { selector: '.search-box', text: 'Search anything... (Ctrl+K)' },
                { selector: '.upgrade-btn', text: 'Unlock premium features' }
            ];

            tooltipElements.forEach(item => {
                const element = document.querySelector(item.selector);
                if (element) {
                    element.title = item.text;
                }
            });
        }

        addTooltips();

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Educational Dashboard loaded successfully! 🎓');
            
            // Add welcome animation
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // PHP Integration (for backend functionality)
        // Note: This would typically be handled by separate PHP files
        class DashboardAPI {
            static async fetchStats() {
                try {
                    // In a real application, this would fetch from PHP backend
                    const response = await fetch('/api/dashboard/stats.php');
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.log('Using mock data - PHP backend not available');
                    return {
                        averageScore: 86,
                        newEnrollments: 1250,
                        completedCourses: 3402,
                        activeStudents: 5876
                    };
                }
            }

            static async fetchActivities() {
                try {
                    const response = await fetch('/api/dashboard/activities.php');
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.log('Using mock data - PHP backend not available');
                    return [];
                }
            }

            static async fetchRevenueData() {
                try {
                    const response = await fetch('/api/dashboard/revenue.php');
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.log('Using mock data - PHP backend not available');
                    return {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        values: [65000, 59000, 80000, 81000, 56000, 85000]
                    };
                }
            }
        }

      
