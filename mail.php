<?php
/**
 * Скрипт отправки заявок на email (PHP)
 * Завод «Стекло» — Архитектурные конструкции
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

$name = trim($data['name'] ?? $data['Имя'] ?? 'Не указано');
$phone = trim($data['phone'] ?? $data['Телефон'] ?? 'Не указан');
$title = trim($data['title'] ?? $data['Источник'] ?? 'Заявка с сайта Оконный Завод Стекло');
$details = trim($data['details'] ?? $data['Детали'] ?? '');

if (empty($phone) || $phone === 'Не указан') {
    http_response_code(400);
    echo json_encode(['error' => 'Phone is required']);
    exit;
}

$to = 'oknazavod-gel@yandex.ru';
$subject = "=?UTF-8?B?" . base64_encode("⚡ $title: $name ($phone)") . "?=";

$body = "<!DOCTYPE html><html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #0f172a; background: #f8fafd; padding: 24px;'>";
$body .= "<div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);'>";
$body .= "<div style='border-bottom: 2px solid #0ea5e9; padding-bottom: 16px; margin-bottom: 20px;'>";
$body .= "<span style='font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #0ea5e9; font-weight: bold;'>Оконный Завод • Стекло</span>";
$body .= "<h2 style='margin: 6px 0 0; color: #0f172a; font-size: 22px;'>⚡ " . htmlspecialchars($title) . "</h2>";
$body .= "</div>";

$body .= "<table style='width: 100%; border-collapse: collapse;'>";
$body .= "<tr><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 13px; width: 140px;'>Клиент:</td><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-weight: bold; color: #0f172a;'>" . htmlspecialchars($name) . "</td></tr>";
$body .= "<tr><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 13px;'>Телефон:</td><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 16px; font-weight: bold;'><a href='tel:" . htmlspecialchars($phone) . "' style='color: #0ea5e9; text-decoration: none;'>" . htmlspecialchars($phone) . "</a></td></tr>";

if (!empty($details)) {
    $body .= "<tr><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 13px;'>Детали / Вопрос:</td><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155;'>" . nl2br(htmlspecialchars($details)) . "</td></tr>";
}

foreach ($data as $k => $v) {
    if (!in_array($k, ['name', 'phone', 'title', 'details', 'Имя', 'Телефон', 'Источник', 'Детали', 'extraFields']) && !empty($v)) {
        $val = is_array($v) ? implode(', ', $v) : $v;
        $body .= "<tr><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 13px;'>" . htmlspecialchars($k) . ":</td><td style='padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155;'>" . htmlspecialchars($val) . "</td></tr>";
    }
}

$body .= "</table>";
$body .= "<div style='margin-top: 24px; padding-top: 14px; border-top: 1px solid #f1f5f9; color: #94a3b8; font-size: 12px; display: flex; justify-content: space-between;'>";
$body .= "<span>Заявка с сайта <b>oknazavod.ru</b></span>";
$body .= "<span>" . date('d.m.Y H:i') . " МСК</span>";
$body .= "</div>";
$body .= "</div></body></html>";

$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=utf-8',
    'From: Оконный Завод Стекло <noreply@' . ($_SERVER['SERVER_NAME'] ?? 'oknazavod.ru') . '>',
    'X-Mailer: PHP/' . phpversion()
];

$sent = @mail($to, $subject, $body, implode("\r\n", $headers));

if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
} else {
    // Не крашим клиент, отдаем статус
    echo json_encode(['success' => false, 'message' => 'PHP mail() dispatch attempted']);
}
