<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kegiatan Hari Ini</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo Belajar">
                    <h1>Jadwal Kegiatan</h1>
                </div>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <nav>
                    <ul>
                        <li><a href="#dashboard">Dashboard</a></li>
                        <li><a href="#calendar">Kalender</a></li>
                        <li><a href="#activities">Kegiatan</a></li>
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Popup Konfirmasi Hapus -->
    <div id="confirmPopup" class="popup-overlay" style="display: none;">
        <div class="popup-box">
            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus kegiatan ini?</p>
            <div class="popup-buttons">
                <button id="confirmYes" class="btn-yes">Ya, Hapus</button>
                <button id="confirmNo" class="btn-no">Batal</button>
            </div>
        </div>
    </div>


    <!-- Bottom Navigation for Mobile -->
    <div class="bottom-nav">
        <ul>
            <li><a href="#dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#calendar"><i class="fas fa-calendar-alt"></i> Kalender</a></li>
            <li><a href="#activities"><i class="fas fa-list"></i> Kegiatan</a></li>
            <li><a href="#" id="mobile-add-btn"><i class="fas fa-plus"></i> Tambah</a></li>
            <li><a href="{{ url('/logout') }}" id="mobile-add-btn"><i class="fas fa-plus"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Desktop Add Button -->
    <div class="desktop-add-btn" id="desktop-add-btn">
        <i class="fas fa-plus"></i>
    </div>

    <div class="modal" id="activity-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Tambah Kegiatan Baru</h2>
                <button class="close-modal" id="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="activity-form">
                    <input type="hidden" id="activity-id">
                    <div class="form-group">
                        <label for="activity-title">Judul Kegiatan</label>
                        <input type="text" class="form-control" id="activity-title" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-description">Deskripsi</label>
                        <textarea class="form-control" id="activity-description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="activity-date">Tanggal</label>
                        <input type="date" class="form-control" id="activity-date" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-time">Waktu</label>
                        <input type="time" class="form-control" id="activity-time" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-location">Lokasi</label>
                        <input type="text" class="form-control" id="activity-location" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-category">Kategori</label>
                        <select class="form-control" id="activity-category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Matematika">Matematika</option>
                            <option value="Bahasa">Bahasa</option>
                            <option value="Sains">Sains</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Seni">Seni</option>
                            <option value="Teknologi">Teknologi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activity-duration">Durasi (jam)</label>
                        <input type="number" class="form-control" id="activity-duration" min="0.5" step="0.5" required>
                    </div>
                    <div class="form-group">
                        <label for="activity-deadline">Deadline</label>
                        <input type="datetime-local" class="form-control" id="activity-deadline">
                    </div>
                    <div class="form-group">
                        <label for="activity-status">Status</label>
                        <select class="form-control" id="activity-status">
                            <option value="pending">Belum Selesai</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activity-image">Gambar</label>
                        <div class="file-upload">
                            <div class="file-upload-btn">
                                <i class="fas fa-upload"></i>
                                <span>Pilih Gambar</span>
                            </div>
                            <input type="file" id="activity-image" accept="image/*">
                        </div>
                        <div class="image-preview" id="image-preview">
                            <img id="preview-img" src="#" alt="Preview Gambar">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-button">
                        <span id="submit-text">Tambah Kegiatan</span>
                        <div class="loading" id="submit-loading" style="display: none;"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notification-text">Operasi berhasil!</span>
    </div>

    <!-- Dashboard Section -->
    <section id="dashboard">
        <div class="container">
            <div class="section-title">
                <h2>Dashboard Kegiatan Hari Ini</h2>
            </div>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-book-open"></i>
                    <h3 id="total-activities">0</h3>
                    <p>Total Kegiatan</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar-day"></i>
                    <h3 id="upcoming-activities">0</h3>
                    <p>Kegiatan Mendatang</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h3 id="completed-activities">0</h3>
                    <p>Kegiatan Selesai</p>
                </div>
            </div>
            <div class="container" id="calendar">
                <div class="section-title text-center">
                    <h2>Kalender Kegiatan</h2>
                </div>

                <div class="calendar-container">
                    <div class="calendar-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" id="prev-month"><i class="fas fa-chevron-left"></i></button>
                        <h3 id="current-month">November 2023</h3>
                        <button class="btn btn-primary" id="next-month"><i class="fas fa-chevron-right"></i></button>
                    </div>

                    <div class="calendar-grid" id="calendar-days">
                        <!-- Calendar days will be generated by JavaScript -->
                    </div>
                </div>
            </div>

    </section>

    <!-- Activities Section -->
    <section id="activities">
        <div class="container">
            <div class="section-title">
                <h2>Daftar Kegiatan Hari Ini</h2>
            </div>

            <div class="search-filter">
                <div class="search-box">
                    <input type="text" class="form-control" id="search-input" placeholder="Cari kegiatan...">
                </div>
                <div class="filter-category">
                    <select class="form-control" id="category-filter">
                        <option value="">Semua Kategori</option>
                        <option value="Matematika">Matematika</option>
                        <option value="Bahasa">Bahasa</option>
                        <option value="Sains">Sains</option>
                        <option value="Sejarah">Sejarah</option>
                        <option value="Seni">Seni</option>
                        <option value="Teknologi">Teknologi</option>
                    </select>
                </div>
                <div class="filter-status">
                    <select class="form-control" id="status-filter">
                        <option value="">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="pending">Belum Selesai</option>
                        <option value="overdue">Terlambat</option>
                    </select>
                </div>
            </div>

            <div class="activities-grid" id="activities-container">
                <!-- Activity cards will be generated by JavaScript -->
            </div>
        </div>
    </section>

<script>
        // API endpoints
        const API_BASE = 'api.php';

        const activitiesContainer = document.getElementById('activities-container');
        const calendarDays = document.getElementById('calendar-days');
        const currentMonthElement = document.getElementById('current-month');
        const activityForm = document.getElementById('activity-form');
        const searchInput = document.getElementById('search-input');
        const categoryFilter = document.getElementById('category-filter');
        const statusFilter = document.getElementById('status-filter');
        const notification = document.getElementById('notification');
        const notificationText = document.getElementById('notification-text');
        const activityModal = document.getElementById('activity-modal');
        const modalTitle = document.getElementById('modal-title');
        const submitButton = document.getElementById('submit-button');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');
        const desktopAddBtn = document.getElementById('desktop-add-btn');
        const mobileAddBtn = document.getElementById('mobile-add-btn');
        const closeModal = document.getElementById('close-modal');
        const imageUpload = document.getElementById('activity-image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

  
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

   
        function init() {
            loadActivities();
            renderCalendar();
            setupEventListeners();
        }

        // Load activities from API
        async function loadActivities() {
            try {
                const response = await fetch(`${API_BASE}?action=get_activities`);
                const result = await response.json();

                if (result.success) {
                    renderActivities(result.data);
                    updateDashboardStats(result.data);
                } else {
                    showNotification('Gagal memuat data kegiatan: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error loading activities:', error);
                showNotification('Date Berhasil Di muat', 'success');
            }
        }

    
        function renderActivities(activities) {
            activitiesContainer.innerHTML = '';

            if (activities.length === 0) {
                activitiesContainer.innerHTML = '<p>Tidak ada kegiatan yang ditemukan.</p>';
                return;
            }

            activities.forEach(activity => {
                const activityCard = document.createElement('div');
                activityCard.className = 'activity-card';

             
                let statusBadge = '';
                if (activity.status === 'completed') {
                    statusBadge = '<span class="status-badge status-completed">Selesai</span>';
                } else if (activity.status === 'overdue') {
                    statusBadge = '<span class="status-badge status-overdue">Terlambat</span>';
                } else {
                    statusBadge = '<span class="status-badge status-pending">Belum Selesai</span>';
                }

                activityCard.innerHTML = `
                    <div class="activity-img">
                        <img src="${activity.image || 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1122&q=80'}" alt="${activity.title}">
                    </div>
                    <div class="activity-info">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span class="activity-category">${activity.category}</span>
                            ${statusBadge}
                        </div>
                        <h3>${activity.title}</h3>
                        <div class="activity-meta">
                            <span><i class="far fa-calendar"></i> ${formatDate(activity.date)}</span>
                            <span><i class="far fa-clock"></i> ${activity.time}</span>
                        </div>
                        <p>${activity.description}</p>
                        <div class="activity-meta">
                            <span><i class="fas fa-map-marker-alt"></i> ${activity.location}</span>
                            <span><i class="fas fa-hourglass-half"></i> ${activity.duration} jam</span>
                        </div>
                        ${activity.deadline ? `<div class="activity-meta"><span><i class="fas fa-exclamation-circle"></i> Deadline: ${formatDateTime(activity.deadline)}</span></div>` : ''}
                        <div class="activity-actions">
                            <button class="btn btn-primary edit-activity" data-id="${activity.id}">Edit</button>
                            <button class="btn ${activity.status === 'completed' ? 'btn-warning' : 'btn-success'} toggle-status" data-id="${activity.id}">
                                ${activity.status === 'completed' ? 'Tandai Belum Selesai' : 'Tandai Selesai'}
                            </button>
                            <button class="btn btn-danger delete-activity" data-id="${activity.id}">Hapus</button>
                        </div>
                    </div>
                `;

                activitiesContainer.appendChild(activityCard);
            });

        
            document.querySelectorAll('.edit-activity').forEach(button => {
                button.addEventListener('click', function () {
                    const activityId = parseInt(this.getAttribute('data-id'));
                    editActivity(activityId);
                });
            });

            document.querySelectorAll('.toggle-status').forEach(button => {
                button.addEventListener('click', function () {
                    const activityId = parseInt(this.getAttribute('data-id'));
                    toggleActivityStatus(activityId);
                });
            });

            document.querySelectorAll('.delete-activity').forEach(button => {
                button.addEventListener('click', function () {
                    const activityId = parseInt(this.getAttribute('data-id'));
                    deleteActivity(activityId);
                });
            });
        }

    
        function renderCalendar() {
        
            calendarDays.innerHTML = '';

           
            const days = ['Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            days.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                calendarDays.appendChild(dayElement);
            });

        
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

          
            let startDay = firstDay === 0 ? 6 : firstDay - 1;

         
            for (let i = 0; i < startDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-date';
                calendarDays.appendChild(emptyCell);
            }

         
            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                dateCell.className = 'calendar-date';
                dateCell.textContent = day;

              
                const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

               
                dateCell.addEventListener('click', function () {
                 
                    document.querySelectorAll('.calendar-date').forEach(cell => {
                        cell.classList.remove('selected');
                    });

               
                    this.classList.add('selected');

                
                    filterActivitiesByDate(dateString);
                });

                calendarDays.appendChild(dateCell);
            }

          
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        }

      
        function updateCalendarWithActivities(activities) {
          
            document.querySelectorAll('.calendar-date.has-event').forEach(cell => {
                cell.classList.remove('has-event');
            });

          
            activities.forEach(activity => {
                const activityDate = new Date(activity.date);
                if (activityDate.getMonth() === currentMonth && activityDate.getFullYear() === currentYear) {
                    const day = activityDate.getDate();
                    const dateCells = document.querySelectorAll('.calendar-date');
                    dateCells.forEach(cell => {
                        if (parseInt(cell.textContent) === day) {
                            cell.classList.add('has-event');
                        }
                    });
                }
            });
        }

  
        async function filterActivitiesByDate(date) {
            try {
                const response = await fetch(`${API_BASE}?action=get_activities&date=${date}`);
                const result = await response.json();

                if (result.success) {
                    renderActivities(result.data);
                } else {
                    showNotification('Gagal memfilter kegiatan: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error filtering activities:', error);
                showNotification('Terjadi kesalahan saat memfilter data', 'error');
            }
        }

        function updateDashboardStats(activities) {
            const totalActivities = activities.length;
            const today = new Date().toISOString().split('T')[0];
            const upcomingActivities = activities.filter(activity => activity.date >= today && activity.status !== 'completed').length;
            const completedActivities = activities.filter(activity => activity.status === 'completed').length;
            const totalStudyHours = activities.reduce((sum, activity) => sum + parseFloat(activity.duration), 0);

            document.getElementById('total-activities').textContent = totalActivities;
            document.getElementById('upcoming-activities').textContent = upcomingActivities;
            document.getElementById('completed-activities').textContent = completedActivities;
            document.getElementById('study-hours').textContent = totalStudyHours;

       
            updateCalendarWithActivities(activities);
        }


        function formatDate(dateString) {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', options);
        }

        function formatDateTime(dateTimeString) {
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const date = new Date(dateTimeString);
            return date.toLocaleDateString('id-ID', options);
        }

   
        function showNotification(message, type = 'success') {
            notificationText.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        
        async function editActivity(activityId) {
            try {
                const response = await fetch(`${API_BASE}?action=get_activity&id=${activityId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success && result.data) {
                    const activity = result.data;

                    const fields = [
                        ['activity-id', activity.id],
                        ['activity-title', activity.title],
                        ['activity-description', activity.description],
                        ['activity-date', activity.date],
                        ['activity-time', activity.time],
                        ['activity-location', activity.location],
                        ['activity-category', activity.category],
                        ['activity-duration', activity.duration],
                        ['activity-status', activity.status],
                    ];

                    fields.forEach(([id, value]) => {
                        const el = document.getElementById(id);
                        if (el) el.value = value || '';
                    });

                   
                    const deadlineInput = document.getElementById('activity-deadline');
                    if (deadlineInput) {
                        if (activity.deadline) {
                            const deadline = new Date(activity.deadline);
                            deadlineInput.value = deadline.toISOString().slice(0, 16);
                        } else {
                            deadlineInput.value = '';
                        }
                    }

                  
                    if (activity.image) {
                        previewImg.src = activity.image;
                        imagePreview.style.display = 'block';
                    } else {
                        imagePreview.style.display = 'none';
                        previewImg.src = '';
                    }

                  
                    modalTitle.textContent = 'Edit Kegiatan';
                    submitText.textContent = 'Update Kegiatan';

                  
                    showNotification('Berhasil memuat data kegiatan untuk diedit!', 'success');

                  
                    openModal();

                } else {
                    showNotification('Gagal memuat data kegiatan: ' + (result.message || 'Data tidak ditemukan'), 'error');
                }
            } catch (error) {
                console.error('Error loading activity:', error);
                showNotification('Terjadi kesalahan saat memuat data kegiatan', 'error');
            }
        }

        async function toggleActivityStatus(activityId) {
            try {
                const response = await fetch(`${API_BASE}?action=get_activity&id=${activityId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    const activity = result.data;
                    const newStatus = activity.status === 'completed' ? 'pending' : 'completed';

                
                    const updateResponse = await fetch(API_BASE, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=update_activity&id=${activityId}&status=${newStatus}`
                    });

                    const updateResult = await updateResponse.json();

                    if (updateResult.success) {
                        await loadActivities();
                        showNotification('Status kegiatan berhasil diubah!');
                    } else {
                        showNotification('Gagal mengubah status: ' + updateResult.message, 'error');
                    }
                } else {
                    showNotification('Gagal memuat data kegiatan', 'error');
                }
            } catch (error) {
                console.error('Error toggling status:', error);
                showNotification('Terjadi kesalahan saat mengubah status', 'error');
            }
        }

   
        async function deleteActivity(activityId) {
          
            const popup = document.getElementById('confirmPopup');
            popup.style.display = 'flex';

            return new Promise((resolve) => {
                const yesBtn = document.getElementById('confirmYes');
                const noBtn = document.getElementById('confirmNo');

                const closePopup = () => {
                    popup.style.display = 'none';
                    yesBtn.removeEventListener('click', confirmHandler);
                    noBtn.removeEventListener('click', cancelHandler);
                };

                const confirmHandler = async () => {
                    closePopup();
                    try {
                        const response = await fetch(API_BASE, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `action=delete_activity&id=${activityId}`
                        });

                        const result = await response.json();

                        if (result.success) {
                            await loadActivities();
                            showNotification('Kegiatan berhasil dihapus!');
                        } else {
                            showNotification('Gagal menghapus kegiatan: ' + result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting activity:', error);
                        showNotification('Terjadi kesalahan saat menghapus kegiatan', 'error');
                    }
                    resolve(true);
                };

                const cancelHandler = () => {
                    closePopup();
                    resolve(false);
                };

                yesBtn.addEventListener('click', confirmHandler);
                noBtn.addEventListener('click', cancelHandler);
            });
        }

        function setupEventListeners() {
            document.querySelector('.mobile-menu').addEventListener('click', function () {
                document.querySelector('nav').classList.toggle('active');
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });

                       
                        if (window.innerWidth <= 768) {
                            document.querySelector('nav').classList.remove('active');
                        }
                    }
                });
            });

          
            document.getElementById('prev-month').addEventListener('click', function () {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
                loadActivities(); 
            });

            document.getElementById('next-month').addEventListener('click', function () {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
                loadActivities(); 
            });

            desktopAddBtn.addEventListener('click', openAddModal);
            mobileAddBtn.addEventListener('click', openAddModal);

            closeModal.addEventListener('click', closeModalFunc);

          
            window.addEventListener('click', function (e) {
                if (e.target === activityModal) {
                    closeModalFunc();
                }
            });

       
            imageUpload.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

   
            activityForm.addEventListener('submit', async function (e) {
                e.preventDefault();

               
                submitText.style.display = 'none';
                submitLoading.style.display = 'inline-block';
                submitButton.disabled = true;

                try {
                    const id = document.getElementById('activity-id').value;
                    const title = document.getElementById('activity-title').value;
                    const description = document.getElementById('activity-description').value;
                    const date = document.getElementById('activity-date').value;
                    const time = document.getElementById('activity-time').value;
                    const location = document.getElementById('activity-location').value;
                    const category = document.getElementById('activity-category').value;
                    const duration = parseFloat(document.getElementById('activity-duration').value);
                    const deadline = document.getElementById('activity-deadline').value;
                    const status = document.getElementById('activity-status').value;
                    const imageFile = document.getElementById('activity-image').files[0];

                   
                    const formData = new FormData();
                    formData.append('action', id ? 'update_activity' : 'add_activity');
                    if (id) formData.append('id', id);
                    formData.append('title', title);
                    formData.append('description', description);
                    formData.append('date', date);
                    formData.append('time', time);
                    formData.append('location', location);
                    formData.append('category', category);
                    formData.append('duration', duration);
                    formData.append('deadline', deadline);
                    formData.append('status', status);
                    if (imageFile) formData.append('image', imageFile);

                    const response = await fetch(API_BASE, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                    
                        activityForm.reset();
                        imagePreview.style.display = 'none';
                        document.getElementById('activity-id').value = '';

                        showNotification(id ? 'Kegiatan berhasil diperbarui!' : 'Kegiatan berhasil ditambahkan!');

                   
                        closeModalFunc();

                   
                        await loadActivities();

                      
                        document.getElementById('activities').scrollIntoView({ behavior: 'smooth' });
                    } else {
                        showNotification('Gagal menyimpan kegiatan: ' + result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error saving activity:', error);
                    showNotification('Terjadi kesalahan saat menyimpan kegiatan', 'error');
                } finally {
                    submitText.style.display = 'inline-block';
                    submitLoading.style.display = 'none';
                    submitButton.disabled = false;
                }
            });
            searchInput.addEventListener('input', filterActivities);
            categoryFilter.addEventListener('change', filterActivities);
            statusFilter.addEventListener('change', filterActivities);
        }

        function openAddModal() {
        
            activityForm.reset();
            imagePreview.style.display = 'none';
            document.getElementById('activity-id').value = '';
            modalTitle.textContent = 'Tambah Kegiatan Baru';
            submitText.textContent = 'Tambah Kegiatan';

       
            openModal();
        }

        function openModal() {
            activityModal.classList.add('show');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModalFunc() {
            activityModal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        async function filterActivities() {
            const searchTerm = searchInput.value.toLowerCase();
            const category = categoryFilter.value;
            const status = statusFilter.value;

            try {
                let url = `${API_BASE}?action=get_activities`;
                if (searchTerm) url += `&search=${encodeURIComponent(searchTerm)}`;
                if (category) url += `&category=${encodeURIComponent(category)}`;
                if (status) url += `&status=${encodeURIComponent(status)}`;

                const response = await fetch(url);
                const result = await response.json();

                if (result.success) {
                    renderActivities(result.data);
                } else {
                    showNotification('Gagal memfilter kegiatan: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error filtering activities:', error);
                showNotification('Terjadi kesalahan saat memfilter data', 'error');
            }
        }

        init();
    </script>
</body>

</html>