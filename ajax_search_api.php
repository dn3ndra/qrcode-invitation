<?php
// search_guests.php - AJAX endpoint for real-time search
include 'auth.php';
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

try {
    if (empty($searchTerm)) {
        // Return recent guests if no search term
        $stmt = $mysqli->prepare("SELECT * FROM guests ORDER BY checkin_time DESC, name ASC LIMIT ?");
        $stmt->bind_param("i", $limit);
    } else {
        // Search by name
        $searchPattern = "%{$searchTerm}%";
        $stmt = $mysqli->prepare("SELECT * FROM guests WHERE name LIKE ? ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                ELSE 3 
            END, name ASC LIMIT ?");
        $exactStart = "{$searchTerm}%";
        $wordStart = "% {$searchTerm}%";
        $stmt->bind_param("sssi", $searchPattern, $exactStart, $wordStart, $limit);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $guests = [];
    while ($row = $result->fetch_assoc()) {
        $guests[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'status' => $row['status'],
            'checkin_time' => $row['checkin_time'],
            'formatted_time' => $row['checkin_time'] ? date('d/m/Y H:i', strtotime($row['checkin_time'])) : null
        ];
    }
    
    // Also search manual guests
    if (empty($searchTerm)) {
        $manualStmt = $mysqli->prepare("SELECT * FROM manual_guests ORDER BY checkin_time DESC LIMIT ?");
        $manualStmt->bind_param("i", $limit);
    } else {
        $manualStmt = $mysqli->prepare("SELECT * FROM manual_guests WHERE name LIKE ? ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                ELSE 3 
            END, name ASC LIMIT ?");
        $manualStmt->bind_param("sssi", $searchPattern, $exactStart, $wordStart, $limit);
    }
    
    $manualStmt->execute();
    $manualResult = $manualStmt->get_result();
    
    $manualGuests = [];
    while ($row = $manualResult->fetch_assoc()) {
        $manualGuests[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'checkin_time' => $row['checkin_time'],
            'formatted_time' => $row['checkin_time'] ? date('d/m/Y H:i', strtotime($row['checkin_time'])) : null
        ];
    }
    
    echo json_encode([
        'success' => true,
        'search_term' => $searchTerm,
        'guests' => $guests,
        'manual_guests' => $manualGuests,
        'total_found' => count($guests) + count($manualGuests)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error occurred',
        'message' => $e->getMessage()
    ]);
}
?>