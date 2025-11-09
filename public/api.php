<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Konfigurasi database
$host = 'localhost';
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'belajar_management'; // Ganti dengan nama database Anda

// Koneksi ke database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $e->getMessage()]);
    exit;
}

// Fungsi untuk mendapatkan semua kegiatan
function getActivities($pdo, $filters = []) {
    $sql = "SELECT * FROM activities WHERE 1=1";
    $params = [];
    
    if (isset($filters['date']) && !empty($filters['date'])) {
        $sql .= " AND date = ?";
        $params[] = $filters['date'];
    }
    
    if (isset($filters['category']) && !empty($filters['category'])) {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
    }
    
    if (isset($filters['status']) && !empty($filters['status'])) {
        $sql .= " AND status = ?";
        $params[] = $filters['status'];
    }
    
    if (isset($filters['search']) && !empty($filters['search'])) {
        $sql .= " AND (title LIKE ? OR description LIKE ?)";
        $searchTerm = "%{$filters['search']}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " ORDER BY date, time";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Update status berdasarkan deadline
        $updatedActivities = updateActivityStatuses($activities);
        
        return ['success' => true, 'data' => $updatedActivities];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal mengambil data: ' . $e->getMessage()];
    }
}

// Fungsi untuk mendapatkan satu kegiatan berdasarkan ID
function getActivity($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        $activity = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($activity) {
            return ['success' => true, 'data' => $activity];
        } else {
            return ['success' => false, 'message' => 'Kegiatan tidak ditemukan'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal mengambil data: ' . $e->getMessage()];
    }
}

// Fungsi untuk menambahkan kegiatan baru
function addActivity($pdo, $data) {
    // Validasi data yang diperlukan
    $required = ['title', 'description', 'date', 'time', 'location', 'category', 'duration'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => "Field $field harus diisi"];
        }
    }
    
    try {
        $sql = "INSERT INTO activities (title, description, date, time, location, category, duration, deadline, status, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // Handle upload gambar
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $imagePath = $imagePath;
            }
        }
        
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['date'],
            $data['time'],
            $data['location'],
            $data['category'],
            $data['duration'],
            $data['deadline'] ?? null,
            $data['status'] ?? 'pending',
            $imagePath
        ]);
        
        return ['success' => true, 'message' => 'Kegiatan berhasil ditambahkan', 'id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal menambahkan kegiatan: ' . $e->getMessage()];
    }
}

// Fungsi untuk memperbarui kegiatan
function updateActivity($pdo, $data) {
    if (empty($data['id'])) {
        return ['success' => false, 'message' => 'ID kegiatan harus disertakan'];
    }
    
    try {
        // Cek apakah kegiatan exists
        $checkStmt = $pdo->prepare("SELECT id FROM activities WHERE id = ?");
        $checkStmt->execute([$data['id']]);
        if (!$checkStmt->fetch()) {
            return ['success' => false, 'message' => 'Kegiatan tidak ditemukan'];
        }
        
        $sql = "UPDATE activities SET title = ?, description = ?, date = ?, time = ?, location = ?, 
                category = ?, duration = ?, deadline = ?, status = ?";
        
        $params = [
            $data['title'],
            $data['description'],
            $data['date'],
            $data['time'],
            $data['location'],
            $data['category'],
            $data['duration'],
            $data['deadline'] ?? null,
            $data['status'] ?? 'pending'
        ];
        
        // Handle upload gambar jika ada
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $sql .= ", image = ?";
                $params[] = $imagePath;
            }
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $data['id'];
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return ['success' => true, 'message' => 'Kegiatan berhasil diperbarui'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal memperbarui kegiatan: ' . $e->getMessage()];
    }
}

// Fungsi untuk menghapus kegiatan
function deleteActivity($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Kegiatan berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Kegiatan tidak ditemukan'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Gagal menghapus kegiatan: ' . $e->getMessage()];
    }
}

// Fungsi untuk memperbarui status kegiatan berdasarkan deadline
function updateActivityStatuses($activities) {
    $now = new DateTime();
    
    foreach ($activities as &$activity) {
        if ($activity['status'] === 'completed') {
            continue; // Jangan ubah status jika sudah selesai
        }
        
        if ($activity['deadline']) {
            $deadline = new DateTime($activity['deadline']);
            if ($deadline < $now) {
                $activity['status'] = 'overdue';
            }
        }
    }
    
    return $activities;
}

// Handle request
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_activities':
        $filters = [
            'date' => $_GET['date'] ?? '',
            'category' => $_GET['category'] ?? '',
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $result = getActivities($pdo, $filters);
        echo json_encode($result);
        break;
        
    case 'get_activity':
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID kegiatan harus disertakan']);
        } else {
            $result = getActivity($pdo, $id);
            echo json_encode($result);
        }
        break;
        
    case 'add_activity':
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? '',
            'time' => $_POST['time'] ?? '',
            'location' => $_POST['location'] ?? '',
            'category' => $_POST['category'] ?? '',
            'duration' => $_POST['duration'] ?? '',
            'deadline' => $_POST['deadline'] ?? '',
            'status' => $_POST['status'] ?? 'pending'
        ];
        $result = addActivity($pdo, $data);
        echo json_encode($result);
        break;
        
    case 'update_activity':
        $data = [
            'id' => $_POST['id'] ?? '',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? '',
            'time' => $_POST['time'] ?? '',
            'location' => $_POST['location'] ?? '',
            'category' => $_POST['category'] ?? '',
            'duration' => $_POST['duration'] ?? '',
            'deadline' => $_POST['deadline'] ?? '',
            'status' => $_POST['status'] ?? 'pending'
        ];
        
        // Jika hanya update status
        if (isset($_POST['status']) && count($_POST) == 2) {
            try {
                $stmt = $pdo->prepare("UPDATE activities SET status = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['id']]);
                $result = ['success' => true, 'message' => 'Status berhasil diperbarui'];
            } catch (PDOException $e) {
                $result = ['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()];
            }
        } else {
            $result = updateActivity($pdo, $data);
        }
        
        echo json_encode($result);
        break;
        
    case 'delete_activity':
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID kegiatan harus disertakan']);
        } else {
            $result = deleteActivity($pdo, $id);
            echo json_encode($result);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
        break;
}
?>