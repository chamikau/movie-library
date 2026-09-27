<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}
require dirname(__DIR__) . '/lib/PHPMailer/Exception.php';
require dirname(__DIR__) . '/lib/PHPMailer/PHPMailer.php';
require dirname(__DIR__) . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

function clean(string $value): string {
    return trim(preg_replace('/[\r\n\t]+/', ' ', $value) ?? '');
}

function errorResponse(string $message, int $status = 422): never {
    http_response_code($status);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

$firstName = clean((string)($_POST['first_name'] ?? ''));
$lastName  = clean((string)($_POST['last_name'] ?? ''));
$email     = clean((string)($_POST['email'] ?? ''));
$phone     = clean((string)($_POST['phone'] ?? ''));
$comments  = trim((string)($_POST['comments'] ?? ''));

if ($firstName === '' || mb_strlen($firstName) > 80) {
    errorResponse('First name is required.');
}

if ($lastName === '' || mb_strlen($lastName) > 80) {
    errorResponse('Last name is required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    errorResponse('Please enter a valid email address.');
}

if ($phone !== '' && !preg_match('/^[0-9+() .-]{7,30}$/', $phone)) {
    errorResponse('Please enter a valid phone number.');
}

if ($comments === '' || mb_strlen($comments) > 2000) {
    errorResponse('Comments are required and must be under 2000 characters.');
}

$submission = [
    'id' => bin2hex(random_bytes(8)),
    'submitted_at' => date('c'),
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $email,
    'phone' => $phone,
    'comments' => $comments
];

$file = dirname(__DIR__) . '/data/submissions.json';

$existing = [];
if (file_exists($file)) {
    $decoded = json_decode(file_get_contents($file), true);
    if (is_array($decoded)) {
        $existing = $decoded;
    }
}

$existing[] = $submission;

if (file_put_contents(
    $file,
    json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
    LOCK_EX
) === false) {
    errorResponse('Could not save your submission.', 500);
}
$smtpHost       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
$smtpPort       = (int)(getenv('SMTP_PORT') ?: 587);
$smtpUsername   = getenv('SMTP_USERNAME') ?: 'your-email@gmail.com';
$smtpPassword   = getenv('SMTP_PASSWORD') ?: 'your-app-password';
$smtpFromEmail  = getenv('SMTP_FROM_EMAIL') ?: $smtpUsername;
$smtpFromName   = 'Movie Library';

$admins = [
    'dumidu.kodithuwakku@ebeyonds.com',
    'prabhath.senadheera@ebeyonds.com'
];

function makeMailer(
    string $host,
    int $port,
    string $username,
    string $password,
    string $fromEmail,
    string $fromName
): PHPMailer {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $username;
    $mail->Password   = $password;
    $mail->SMTPSecure = $port === 465
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $port;

    $mail->setFrom($fromEmail, $fromName);
    $mail->isHTML(false);
    $mail->CharSet = 'UTF-8';

    return $mail;
}

$emailErrors = [];
$emailErrorDetails = [];

$adminSubject = "New Website Contact - {$firstName} {$lastName}";

$adminBody =
    "New contact form submission\n\n" .
    "First Name: {$firstName}\n" .
    "Last Name: {$lastName}\n" .
    "Email: {$email}\n" .
    "Phone: " . ($phone ?: 'Not provided') . "\n\n" .
    "Comments:\n{$comments}\n\n" .
    "Submission ID: {$submission['id']}\n" .
    "Submitted: {$submission['submitted_at']}\n";

try {
    $adminMail = makeMailer($smtpHost, $smtpPort, $smtpUsername, $smtpPassword, $smtpFromEmail, $smtpFromName);
    $adminMail->addReplyTo($email, "{$firstName} {$lastName}");
    $adminMail->Subject = $adminSubject;
    $adminMail->Body    = $adminBody;

    foreach ($admins as $admin) {
        $adminMail->addAddress($admin);
    }

    $adminMail->send();

} catch (PHPMailerException $e) {
    error_log('Admin notification email failed: ' . $e->getMessage());
    $emailErrors[] = 'admin';
    $emailErrorDetails['admin'] = $e->getMessage();
}

$userSubject = 'Thank you for contacting us';

$userBody =
    "Hello {$firstName},\n\n" .
    "Thank you for contacting us. We have successfully received your enquiry.\n\n" .
    "Your reference: {$submission['id']}\n\n" .
    "We will get back to you as soon as possible.\n\n" .
    "Regards,\nMovie Library";

try {
    $userMail = makeMailer($smtpHost, $smtpPort, $smtpUsername, $smtpPassword, $smtpFromEmail, $smtpFromName);
    $userMail->addAddress($email, "{$firstName} {$lastName}");
    $userMail->Subject = $userSubject;
    $userMail->Body    = $userBody;
    $userMail->send();

} catch (PHPMailerException $e) {
    error_log('User auto-response email failed: ' . $e->getMessage());
    $emailErrors[] = 'user';
    $emailErrorDetails['user'] = $e->getMessage();
}

if (!empty($emailErrors)) {
    error_log('Contact form submission ' . $submission['id'] . ' saved, but email(s) failed: ' . implode(', ', $emailErrors));
}

echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message was submitted successfully.',
    'debug' => [
        'email_errors' => $emailErrors,
        'email_error_details' => $emailErrorDetails,
        'admin_recipients' => $admins,
        'smtp_host_used' => $smtpHost,
        'smtp_username_used' => $smtpUsername
    ]
]);