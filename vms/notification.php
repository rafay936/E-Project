<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Notification page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .notification-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .notification-item:hover {
            transform: translateX(5px);
        }

        .profile-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .notification-content {
            flex-grow: 1;
        }

        .user-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .notification-preview {
            color: #666;
            font-size: 0.9em;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-profile-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .code-snippet {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        @media screen (max-width: 480px) {
            .notification-item {
                padding: 12px;
            }

            .profile-img {
                width: 40px;
                height: 40px;
            }

            .modal-content {
                padding: 15px;
            }

            .modal-profile-img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body>


    <div class="menu-btn">☰</div>

    <!-- Side Navbar -->
    <nav class="sidenav">
        <div class="sidenav-header">
            <h2>🏥 Hospital Dashboard</h2>
            <div class="close-btn">×</div>
        </div>
        <ul class="nav-menu">
            <a href="dashboard.php">
                <li><i class="fas fa-home"></i>Dashboard</li>
            </a>
            <a href="vaccine.php">
                <li><i class="fas fa-syringe"></i>Vaccines</li>
            </a>
            <a href="beds.php">
                <li><i class="fas fa-bed"></i>Beds</li>
            </a>
            <a href="notification.php">
                <li><i class="fas fa-bell"></i>Notifications</li>
            </a>
            <a href="profile.php">
                <li><i class="fas fa-user"></i>Profile</li>
            </a>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <nav class="breadcrumb">
            <ol>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ol>
        </nav>
        <!-- Notifications section Start -->
        <div class="container">
            <h1 style="text-align: center; margin-bottom: 25px;">Notifications</h1>

            <div class="notification-list">
                <!-- Notification 1 -->
                <div class="notification-item" onclick="showModal(0)">
                    <img src="profile-img.jpeg" class="profile-img" alt="Profile">
                    <div class="notification-content">
                        <div class="user-name">user 1</div>
                        <div class="notification-preview">New message received</div>
                    </div>
                </div>

                <!-- Notification 2 -->
                <div class="notification-item" onclick="showModal(1)">
                    <img src="profile-img.jpeg" class="profile-img" alt="Profile">
                    <div class="notification-content">
                        <div class="user-name">user 2</div>
                        <div class="notification-preview">Code review requested</div>
                    </div>
                </div>

                <!-- Notification 3 -->
                <div class="notification-item" onclick="showModal(2)">
                    <img src="profile-img.jpeg" class="profile-img" alt="Profile">
                    <div class="notification-content">
                        <div class="user-name">user 3</div>
                        <div class="notification-preview">Update available</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal" id="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()"><i class="fas fa-close"></i></span>
                <div class="modal-header">
                    <img id="modalProfile" class="modal-profile-img" src="" alt="Profile">
                    <div>
                        <h2 id="modalName"></h2>
                    </div>
                </div>
                <div class="code-snippet" id="codeContent">
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Cupiditate quaerat est atque at
                        cumque deserunt fugit magni, tempora autem minus saepe eaque explicabo odio consectetur ex
                        nesciunt soluta, maiores doloribus!
                        Praesentium tenetur eveniet vel, recusandae est velit magni quo dicta distinctio

                    </p>
                </div>
            </div>
        </div>
    </div>
        <!-- Footer -->
        <footer>
            <p>&copy;Copy |All Rights Reserved VMS</p>
        </footer>
    </main>


<script>
    const users = [
        {
            name: "user 1",
            profile: "profile-img.jpeg"
        },
        {
            name: "user 2",
            profile: "profile-img.jpeg"
        },
        {
            name: "user 3",
            profile: "profile-img.jpeg"
        }
    ];

    function showModal(index) {
        const modal = document.getElementById('modal');
        document.getElementById('modalProfile').src = users[index].profile;
        document.getElementById('modalName').textContent = users[index].name;
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('modal');
        if (event.target == modal) {
            closeModal();
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
     </script>
<script src="script.js"></script>
</body>
</html>