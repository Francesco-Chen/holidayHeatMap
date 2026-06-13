<?php
/**
 * api.php — REST-style backend for Holiday Heatmap
 *
 * Endpoints (all POST with JSON body):
 *   action=get_employees         → returns employee list
 *   action=save_employees        → saves employee list (admin)
 *   action=get_vacations         → returns all vacation data
 *   action=save_vacation         → saves vacation dates for one employee
 *   action=get_all               → returns employees + vacations + meta
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$dataDir = __DIR__ . '/data';
$employeesFile = $dataDir . '/employees.json';
$vacationsFile = $dataDir . '/vacations.json';
$holidaysFile = $dataDir . '/holidays.json';

// Ensure data directory exists
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Helper: read JSON file safely
function readJson(string $path): array {
    if (!file_exists($path)) return [];
    $content = file_get_contents($path);
    if ($content === false || $content === '') return [];
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

// Helper: write JSON file safely
function writeJson(string $path, $data): bool {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json, LOCK_EX) !== false;
}

// Parse incoming request
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!is_array($input) || empty($input['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing action parameter.']);
    exit;
}

$action = $input['action'];

switch ($action) {

    // ── Get employee list ──────────────────────────────────────
    case 'get_employees':
        $employees = readJson($employeesFile);
        echo json_encode(['success' => true, 'employees' => $employees]);
        break;

    // ── Save employee list (admin) ─────────────────────────────
    case 'save_employees':
        if (!isset($input['employees']) || !is_array($input['employees'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid employees data.']);
            exit;
        }
        // Clean and deduplicate names
        $names = array_values(array_unique(array_filter(array_map('trim', $input['employees']))));
        writeJson($employeesFile, $names);

        // Clean up vacations for removed employees
        $vacations = readJson($vacationsFile);
        $cleaned = [];
        foreach ($vacations as $name => $dates) {
            if (in_array($name, $names)) {
                $cleaned[$name] = $dates;
            }
        }
        writeJson($vacationsFile, (object)$cleaned);

        echo json_encode(['success' => true, 'employees' => $names]);
        break;

    // ── Get all vacation data ──────────────────────────────────
    case 'get_vacations':
        $vacations = readJson($vacationsFile);
        echo json_encode(['success' => true, 'vacations' => (object)$vacations]);
        break;

    // ── Save vacation dates for a single employee ──────────────
    case 'save_vacation':
        if (empty($input['employee']) || !isset($input['dates']) || !is_array($input['dates'])) {
            echo json_encode(['success' => false, 'error' => 'Missing employee or dates.']);
            exit;
        }
        $employee = trim($input['employee']);
        $dates = $input['dates'];

        // Validate employee exists
        $employees = readJson($employeesFile);
        if (!in_array($employee, $employees)) {
            echo json_encode(['success' => false, 'error' => 'Employee not found: ' . $employee]);
            exit;
        }

        // Validate date formats
        foreach ($dates as $d) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) {
                echo json_encode(['success' => false, 'error' => 'Invalid date format: ' . $d]);
                exit;
            }
        }

        $vacations = readJson($vacationsFile);
        // Sort and deduplicate dates
        $dates = array_values(array_unique($dates));
        sort($dates);
        $vacations[$employee] = $dates;
        writeJson($vacationsFile, (object)$vacations);

        echo json_encode(['success' => true]);
        break;

    // ── Get holidays config ────────────────────────────────────
    case 'get_holidays':
        $holidays = readJson($holidaysFile);
        if (empty($holidays)) {
            // Default structure if missing
            $holidays = ['rules' => [], 'collective' => []];
        }
        echo json_encode(['success' => true, 'holidays' => $holidays]);
        break;

    // ── Save holidays config ───────────────────────────────────
    case 'save_holidays':
        if (!isset($input['holidays']) || !is_array($input['holidays'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid holidays data.']);
            exit;
        }
        writeJson($holidaysFile, $input['holidays']);
        echo json_encode(['success' => true]);
        break;

    // ── Get everything in one call ─────────────────────────────
    case 'get_all':
        $employees = readJson($employeesFile);
        $vacations = readJson($vacationsFile);
        $holidays = readJson($holidaysFile);
        if (empty($holidays)) {
            $holidays = ['rules' => [], 'collective' => []];
        }
        echo json_encode([
            'success'    => true,
            'employees'  => $employees,
            'vacations'  => (object)$vacations,
            'holidays'   => $holidays,
            'totalCount' => count($employees),
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Unknown action: ' . $action]);
        break;
}
