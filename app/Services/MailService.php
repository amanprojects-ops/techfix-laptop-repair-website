<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

class MailService
{
    private string $driver;
    private string $host;
    private int $port;
    private string $encryption;
    private string $username;
    private string $password;
    private string $fromAddress;
    private string $fromName;
    private string $replyTo;

    public function __construct(?array $customConfig = null)
    {
        $this->driver      = $customConfig['mail_driver']       ?? (string)Setting::get('mail_driver', 'smtp');
        $this->host        = $customConfig['smtp_host']         ?? (string)Setting::get('smtp_host', 'smtp.gmail.com');
        $this->port        = (int)($customConfig['smtp_port']   ?? Setting::get('smtp_port', 587));
        $this->encryption  = strtolower($customConfig['smtp_encryption'] ?? (string)Setting::get('smtp_encryption', 'tls'));
        $this->username    = $customConfig['smtp_username']     ?? (string)Setting::get('smtp_username', '');
        $this->password    = $customConfig['smtp_password']     ?? (string)Setting::get('smtp_password', '');
        $this->fromAddress = $customConfig['mail_from_address'] ?? (string)Setting::get('mail_from_address', 'support@techfix.in');
        $this->fromName    = $customConfig['mail_from_name']    ?? (string)Setting::get('mail_from_name', 'TechFix Laptop Repair');
        $this->replyTo     = $customConfig['mail_reply_to']     ?? (string)Setting::get('mail_reply_to', $this->fromAddress);
    }

    /**
     * Send an email message
     */
    public function send(string|array $to, string $subject, string $htmlBody, string $textBody = '', array $options = []): bool
    {
        $recipients = is_array($to) ? $to : [$to];
        if (empty($recipients)) {
            return false;
        }

        if (empty($textBody)) {
            $textBody = strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $htmlBody));
        }

        return match ($this->driver) {
            'smtp' => $this->sendViaSmtp($recipients, $subject, $htmlBody, $textBody, $options),
            'mail' => $this->sendViaPhpMail($recipients, $subject, $htmlBody, $textBody, $options),
            'log'  => $this->logEmail($recipients, $subject, $htmlBody, $textBody),
            default => $this->sendViaSmtp($recipients, $subject, $htmlBody, $textBody, $options),
        };
    }

    /**
     * Send email using a view template
     */
    public function sendTemplate(string|array $to, string $subject, string $view, array $data = []): bool
    {
        $viewPath = BASE_PATH . '/resources/views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Mail view not found: {$view}");
        }

        extract($data);
        ob_start();
        require $viewPath;
        $htmlBody = ob_get_clean();

        return $this->send($to, $subject, $htmlBody);
    }

    /**
     * Live Test Connection & Diagnostics tool
     * Returns detailed logs for troubleshooting
     */
    public function testConnection(string $testRecipient): array
    {
        $logs = [];
        $logs[] = "ℹ️ Starting SMTP diagnostic test to: {$testRecipient}";
        $logs[] = "ℹ️ Host: {$this->host}:{$this->port} | Encryption: {$this->encryption} | Driver: {$this->driver}";
        $logs[] = "ℹ️ From: \"{$this->fromName}\" <{$this->fromAddress}>";

        if ($this->driver === 'log') {
            $this->logEmail([$testRecipient], 'TechFix SMTP Test Message', '<p>Test email message.</p>', 'Test email message.');
            $logs[] = "✅ Mail driver is set to 'log'. Simulated email written to storage/logs/mail.log";
            return [
                'success' => true,
                'message' => "Logged successfully to storage/logs/mail.log (Mail Driver is 'log').",
                'logs'    => $logs,
            ];
        }

        if ($this->driver === 'mail') {
            $sent = $this->sendViaPhpMail([$testRecipient], 'TechFix PHP mail() Test', '<h3>TechFix PHP mail() Test</h3><p>Your PHP mail() configuration is functioning properly.</p>', 'TechFix PHP mail() Test');
            if ($sent) {
                $logs[] = "✅ Mail sent via PHP mail() successfully.";
                return ['success' => true, 'message' => 'Email sent via PHP mail() successfully.', 'logs' => $logs];
            } else {
                $logs[] = "❌ PHP mail() returned false. Check server sendmail configuration.";
                return ['success' => false, 'message' => 'PHP mail() failed.', 'logs' => $logs];
            }
        }

        // SMTP socket test with verbose logging
        try {
            $subject = 'TechFix SMTP Diagnostic Test Email';
            $htmlBody = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;">
                <div style="text-align: center; padding-bottom: 20px; border-bottom: 2px solid #2563eb;">
                    <h2 style="color: #0f172a; margin: 0;">⚡ TechFix System Manager</h2>
                    <p style="color: #64748b; margin: 5px 0 0 0;">SMTP Test Email</p>
                </div>
                <div style="padding: 24px 0;">
                    <p style="font-size: 16px; color: #334155;">Congratulations! Your SMTP settings have been configured correctly.</p>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
                        <tr style="background: #f8fafc;"><td style="padding: 10px; font-weight: bold; border: 1px solid #e2e8f0; width: 35%;">SMTP Host</td><td style="padding: 10px; border: 1px solid #e2e8f0;">' . htmlspecialchars($this->host, ENT_QUOTES) . '</td></tr>
                        <tr><td style="padding: 10px; font-weight: bold; border: 1px solid #e2e8f0;">SMTP Port</td><td style="padding: 10px; border: 1px solid #e2e8f0;">' . $this->port . '</td></tr>
                        <tr style="background: #f8fafc;"><td style="padding: 10px; font-weight: bold; border: 1px solid #e2e8f0;">Encryption</td><td style="padding: 10px; border: 1px solid #e2e8f0;">' . strtoupper($this->encryption) . '</td></tr>
                        <tr><td style="padding: 10px; font-weight: bold; border: 1px solid #e2e8f0;">Sender</td><td style="padding: 10px; border: 1px solid #e2e8f0;">' . htmlspecialchars($this->fromAddress, ENT_QUOTES) . '</td></tr>
                        <tr style="background: #f8fafc;"><td style="padding: 10px; font-weight: bold; border: 1px solid #e2e8f0;">Sent At</td><td style="padding: 10px; border: 1px solid #e2e8f0;">' . date('d M Y, h:i:s A T') . '</td></tr>
                    </table>
                </div>
                <div style="font-size: 12px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                    This is an automated test email sent from TechFix Admin System Manager.
                </div>
            </div>';
            $textBody = "TechFix SMTP Diagnostic Test\n\nYour SMTP settings are working properly!\nHost: {$this->host}:{$this->port}\nEncryption: {$this->encryption}\nSent At: " . date('d M Y, h:i:s A');

            $this->sendViaSmtp([$testRecipient], $subject, $htmlBody, $textBody, [], $logs);

            $logs[] = "🎉 All SMTP commands executed successfully! Test email delivered to {$testRecipient}.";
            return [
                'success' => true,
                'message' => "Test email successfully sent to {$testRecipient} via SMTP ({$this->host}:{$this->port}).",
                'logs'    => $logs,
            ];
        } catch (\Throwable $e) {
            $logs[] = "❌ Error: " . $e->getMessage();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'logs'    => $logs,
            ];
        }
    }

    /**
     * Native RFC 5321 pure PHP SMTP Client implementation
     */
    private function sendViaSmtp(array $recipients, string $subject, string $htmlBody, string $textBody, array $options = [], ?array &$logs = null): bool
    {
        $timeout = 15;
        $hostPrefix = ($this->encryption === 'ssl') ? 'ssl://' : '';
        $remoteSocket = $hostPrefix . $this->host . ':' . $this->port;

        if ($logs !== null) {
            $logs[] = "🔌 Connecting socket to {$remoteSocket} (Timeout {$timeout}s)...";
        }

        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ]);

        $socket = @stream_socket_client($remoteSocket, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            throw new \RuntimeException("Could not connect to SMTP server ({$remoteSocket}): {$errstr} (Error #{$errno})");
        }

        stream_set_timeout($socket, $timeout);

        try {
            $greeting = $this->readResponse($socket);
            if ($logs !== null) {
                $logs[] = "📥 Server Greeting: " . trim($greeting);
            }
            $this->verifyResponseCode($greeting, [220]);

            $localhost = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $this->sendCommand($socket, "EHLO {$localhost}", [250], $logs);

            // Handle STARTTLS for TLS connection
            if ($this->encryption === 'tls') {
                $this->sendCommand($socket, "STARTTLS", [220], $logs);

                if ($logs !== null) {
                    $logs[] = "🔒 Enabling TLS cryptographic handshake...";
                }

                $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT);
                if (!$crypto) {
                    // Fallback to general TLS
                    $crypto = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                }

                if (!$crypto) {
                    throw new \RuntimeException("TLS encryption handshake with {$this->host} failed.");
                }

                if ($logs !== null) {
                    $logs[] = "✅ TLS handshake established securely.";
                }

                // Send EHLO again after STARTTLS
                $this->sendCommand($socket, "EHLO {$localhost}", [250], $logs);
            }

            // Authentication
            if (!empty($this->username) && !empty($this->password)) {
                if ($logs !== null) {
                    $logs[] = "🔑 Authenticating as {$this->username}...";
                }

                $this->sendCommand($socket, "AUTH LOGIN", [334], $logs);
                $this->sendCommand($socket, base64_encode($this->username), [334], $logs, "USER: [base64 hidden]");
                $this->sendCommand($socket, base64_encode($this->password), [235], $logs, "PASS: [base64 hidden]");

                if ($logs !== null) {
                    $logs[] = "✅ SMTP Authentication accepted (235).";
                }
            }

            // MAIL FROM
            $this->sendCommand($socket, "MAIL FROM:<{$this->fromAddress}>", [250], $logs);

            // RCPT TO
            foreach ($recipients as $recipient) {
                $this->sendCommand($socket, "RCPT TO:<{$recipient}>", [250, 251], $logs);
            }

            // DATA
            $this->sendCommand($socket, "DATA", [354], $logs);

            // Build MIME message
            $mimeMessage = $this->buildMimeMessage($recipients, $subject, $htmlBody, $textBody, $options);
            
            if ($logs !== null) {
                $logs[] = "📤 Transmitting message body (" . strlen($mimeMessage) . " bytes)...";
            }

            fwrite($socket, $mimeMessage . "\r\n.\r\n");
            $dataResponse = $this->readResponse($socket);
            if ($logs !== null) {
                $logs[] = "📥 DATA response: " . trim($dataResponse);
            }
            $this->verifyResponseCode($dataResponse, [250]);

            // QUIT
            $this->sendCommand($socket, "QUIT", [221], $logs);
            fclose($socket);

            return true;

        } catch (\Throwable $e) {
            @fclose($socket);
            throw $e;
        }
    }

    private function sendViaPhpMail(array $recipients, string $subject, string $htmlBody, string $textBody, array $options = []): bool
    {
        $boundary = '=_techfix_' . md5((string)microtime(true));
        $to = implode(', ', $recipients);

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers = [
            'MIME-Version: 1.0',
            "From: \"{$this->fromName}\" <{$this->fromAddress}>",
            "Reply-To: <{$this->replyTo}>",
            "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
            'X-Mailer: TechFix PHP Engine',
        ];

        $body = "--{$boundary}\r\n" .
                "Content-Type: text/plain; charset=UTF-8\r\n" .
                "Content-Transfer-Encoding: base64\r\n\r\n" .
                chunk_split(base64_encode($textBody)) . "\r\n" .
                "--{$boundary}\r\n" .
                "Content-Type: text/html; charset=UTF-8\r\n" .
                "Content-Transfer-Encoding: base64\r\n\r\n" .
                chunk_split(base64_encode($htmlBody)) . "\r\n" .
                "--{$boundary}--";

        return @mail($to, $encodedSubject, $body, implode("\r\n", $headers));
    }

    private function logEmail(array $recipients, string $subject, string $htmlBody, string $textBody): bool
    {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $entry = sprintf(
            "====================================================\nDATE: %s\nTO: %s\nFROM: \"%s\" <%s>\nSUBJECT: %s\n----------------------------------------------------\nTEXT BODY:\n%s\n----------------------------------------------------\nHTML BODY:\n%s\n====================================================\n\n",
            date('Y-m-d H:i:s'),
            implode(', ', $recipients),
            $this->fromName,
            $this->fromAddress,
            $subject,
            $textBody,
            $htmlBody
        );

        return file_put_contents($logDir . '/mail.log', $entry, FILE_APPEND) !== false;
    }

    private function sendCommand($socket, string $command, array $expectedCodes, ?array &$logs = null, ?string $displayCommand = null): string
    {
        $cmdToLog = $displayCommand ?? $command;
        if ($logs !== null) {
            $logs[] = "📤 > " . $cmdToLog;
        }

        fwrite($socket, $command . "\r\n");
        $response = $this->readResponse($socket);

        if ($logs !== null) {
            $logs[] = "📥 < " . trim($response);
        }

        $this->verifyResponseCode($response, $expectedCodes, $cmdToLog);
        return $response;
    }

    private function readResponse($socket): string
    {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            // RFC 5321: multiline replies have hyphen after status code (e.g. 250-SIZE)
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        return $response;
    }

    private function verifyResponseCode(string $response, array $expectedCodes, string $context = ''): void
    {
        $code = (int)substr($response, 0, 3);
        if (!in_array($code, $expectedCodes, true)) {
            $detail = trim($response);
            $msg = "SMTP error for command [{$context}]. Expected code (" . implode(',', $expectedCodes) . "), got ({$code}): {$detail}";
            throw new \RuntimeException($msg);
        }
    }

    private function buildMimeMessage(array $recipients, string $subject, string $htmlBody, string $textBody, array $options = []): string
    {
        $boundary = '=_techfix_' . md5((string)microtime(true));
        $dateStr  = date('r');
        $msgId    = sprintf('<%s.%s@%s>', bin2hex(random_bytes(8)), time(), parse_url($this->host, PHP_URL_HOST) ?? 'techfix.in');
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $toHeader = implode(', ', array_map(fn($email) => "<{$email}>", $recipients));

        $headers = [
            "Date: {$dateStr}",
            "To: {$toHeader}",
            "From: \"{$this->fromName}\" <{$this->fromAddress}>",
            "Reply-To: <{$this->replyTo}>",
            "Subject: {$encodedSubject}",
            "Message-ID: {$msgId}",
            "MIME-Version: 1.0",
            "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
            "X-Mailer: TechFix Pure SMTP Engine 2.0",
        ];

        $lines = [];
        foreach ($headers as $h) {
            $lines[] = $h;
        }
        $lines[] = '';
        $lines[] = "--{$boundary}";
        $lines[] = "Content-Type: text/plain; charset=UTF-8";
        $lines[] = "Content-Transfer-Encoding: base64";
        $lines[] = '';
        $lines[] = chunk_split(base64_encode($textBody));
        $lines[] = "--{$boundary}";
        $lines[] = "Content-Type: text/html; charset=UTF-8";
        $lines[] = "Content-Transfer-Encoding: base64";
        $lines[] = '';
        $lines[] = chunk_split(base64_encode($htmlBody));
        $lines[] = "--{$boundary}--";

        return implode("\r\n", $lines);
    }
}
