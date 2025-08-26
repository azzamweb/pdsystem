<?php

if (!function_exists('formatActivitiesForPdf')) {
    function formatActivitiesForPdf($text) {
        if (empty($text)) {
            return '-';
        }
        
        // Convert markdown to HTML for PDF
        $formatted = $text;
        
        // Convert **bold** to <strong>
        $formatted = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $formatted);
        
        // Convert *italic* to <em>
        $formatted = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $formatted);
        
        // Convert bullet points to HTML list
        $lines = explode("\n", $formatted);
        $formattedLines = [];
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            if (preg_match('/^•\s*(.+)$/', $trimmedLine, $matches)) {
                // Bullet point
                $formattedLines[] = '<li>' . $matches[1] . '</li>';
            } elseif (preg_match('/^\d+\.\s*(.+)$/', $trimmedLine, $matches)) {
                // Numbered list
                $formattedLines[] = '<li>' . $matches[1] . '</li>';
            } else {
                // Regular text
                if (!empty($trimmedLine)) {
                    $formattedLines[] = '<p>' . $trimmedLine . '</p>';
                } else {
                    $formattedLines[] = '<br>';
                }
            }
        }
        
        return implode("\n", $formattedLines);
    }
}
