<?php
session_start();
// Verify guest session if required, or allow public viewing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixTrack Pro | Community Portal</title>
    <!-- Material 3 & Tailwind / Custom CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: #F8F9FA; color: #212121; display: flex; flex-direction: column; min-height: 100vh; }
        header { background: #FFFFFF; padding: 16px 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.25rem; font-weight: 700; color: #212121; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 16px; width: 100%; flex: 1; }
        .card { background: #FFFFFF; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); margin-bottom: 20px; border: 1px solid #E0E0E0; }
        .hero { background: #212121; color: #FFFFFF; }
        .hero h2 { font-size: 1.5rem; margin-bottom: 8px; }
        .hero p { color: #B0BEC5; font-size: 0.9rem; }
        .btn { background: #1A237E; color: #FFFFFF; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; }
        .btn:hover { background: #0d1b60; }
        .nav-bar { position: fixed; bottom: 0; left: 0; width: 100%; background: #FFFFFF; display: flex; justify-content: space-around; padding: 12px 0; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); border-top: 1px solid #E0E0E0; }
        .nav-item { text-align: center; color: #757575; text-decoration: none; font-size: 0.75rem; font-weight: 500; cursor: pointer; }
        .nav-item.active { color: #1A237E; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body>

    <header>
        <h1 id="appHeaderTitle">FixTrack Pro | Feed</h1>
        <a href="logout.php" style="color: #B71C1C; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Logout</a>
    </header>

    <div class="container">
        
        <!-- FEED TAB -->
        <div id="tab-feed" class="tab-content active">
            <div class="card hero">
                <h2 id="welcomeUser">Welcome, Guest!</h2>
                <p>Track real-time community resolution progress and municipal reports.</p>
            </div>
            <div class="card">
                <h3 style="margin-bottom: 12px;">Recent Community Activity</h3>
                <div id="reportsList">
                    <p style="color: #757575; font-size: 0.9rem;">Loading community reports...</p>
                </div>
            </div>
        </div>

        <!-- REPORT TAB -->
        <div id="tab-report" class="tab-content">
            <div class="card">
                <h3 style="margin-bottom: 16px;">Report a Municipal Issue</h3>
                <form id="reportForm">
                    <div style="margin-bottom: 12px;">
                        <label style="display:block; font-size: 0.85rem; margin-bottom: 6px; font-weight: 500;">Issue Title</label>
                        <input type="text" id="reportTitle" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display:block; font-size: 0.85rem; margin-bottom: 6px; font-weight: 500;">Description</label>
                        <textarea id="reportDesc" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"></textarea>
                    </div>
                    <button type="submit" class="btn">Submit Report</button>
                </form>
            </div>
        </div>

        <!-- RAFFLE TAB -->
        <div id="tab-raffle" class="tab-content">
            <div class="card" style="text-align: center; background: linear-gradient(135deg, #1A237E 0%, #3f51b5 100%); color: white;">
                <h2>🎉 Community Rewards Raffle</h2>
                <p style="margin-top: 8px; color: #E8EAF6;">Participate in active municipal feedback raffles and win community perks!</p>
                <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 12px; margin-top: 20px;">
                    <h3 style="font-size: 1.5rem; margin-bottom: 8px;">Active Draw: Community Hero #4</h3>
                    <p style="font-size: 0.9rem; margin-bottom: 16px;">Status: <span style="color: #81C784; font-weight: bold;">LIVE</span></p>
                    <button onclick="enterRaffle()" class="btn" style="background: #FFFFFF; color: #1A237E;">Enter Raffle Ticket Now</button>
                </div>
            </div>
        </div>

        <!-- PROFILE TAB -->
        <div id="tab-profile" class="tab-content">
            <div class="card">
                <h3 style="margin-bottom: 16px;">Guest Profile & Settings</h3>
                <p style="margin-bottom: 8px;"><strong>Account Type:</strong> Community Guest</p>
                <p style="margin-bottom: 16px;"><strong>Status:</strong> Active Verified Session</p>
                <button onclick="alert('Settings updated successfully!')" class="btn" style="background: #757575;">Save Preferences</button>
            </div>
        </div>

    </div>

    <!-- Bottom Navigation Bar matching Android app tabs -->
    <nav class="nav-bar">
        <a class="nav-item active" onclick="switchTab('feed', 'FixTrack Pro | Feed', this)">
            <div style="font-size: 1.2rem;">📰</div>
            Feed
        </a>
        <a class="nav-item" onclick="switchTab('report', 'Report Issue', this)">
            <div style="font-size: 1.2rem;">📷</div>
            Report
        </a>
        <a class="nav-item" onclick="switchTab('raffle', 'Community Raffle', this)">
            <div style="font-size: 1.2rem;">🎟️</div>
            Raffle
        </a>
        <a class="nav-item" onclick="switchTab('profile', 'Guest Profile', this)">
            <div style="font-size: 1.2rem;">👤</div>
            Profile
        </a>
    </nav>

    <script>
        function switchTab(tabName, titleText, element) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            
            // Update Header Title
            document.getElementById('appHeaderTitle').innerText = titleText;

            // Update active nav styling
            document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
            element.classList.add('active');
        }

        function enterRaffle() {
            alert('🎟️ Successfully entered into the FixTrack Community Raffle! Good luck!');
        }

        // Handle Report Submission simulation
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Municipal report submitted successfully!');
            this.reset();
            switchTab('feed', 'FixTrack Pro | Feed', document.querySelectorAll('.nav-item')[0]);
        });
    </script>
</body>
</html>