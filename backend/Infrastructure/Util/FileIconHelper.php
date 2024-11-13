<?php
namespace Infrastructure\Util;

class FileIconHelper
{
    /**
     * Get the icon class based on the file's MIME type.
     *
     * @param string $mimeType
     * @return string
     */
    public static function getFileIcon($mimeType)
    {
        $icons = [
            // Image formats
            'image/jpeg' => 'fa fa-file-image',
            'image/png' => 'fa fa-file-image',
            'image/gif' => 'fa fa-file-image',
            'image/bmp' => 'fa fa-file-image',
            'image/svg+xml' => 'fa fa-file-image',

            // Document formats
            'application/pdf' => 'fa fa-file-pdf',
            'application/msword' => 'fa fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa fa-file-word',
            'application/vnd.ms-excel' => 'fa fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa fa-file-excel',
            'application/vnd.ms-powerpoint' => 'fa fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fa fa-file-powerpoint',

            // Text formats
            'text/plain' => 'fa fa-file-alt',
            'text/csv' => 'fa fa-file-csv',
            'application/rtf' => 'fa fa-file-alt',

            // Audio formats
            'audio/mpeg' => 'fa fa-file-audio',
            'audio/wav' => 'fa fa-file-audio',

            // Video formats
            'video/mp4' => 'fa fa-file-video',
            'video/x-msvideo' => 'fa fa-file-video',
            'video/mpeg' => 'fa fa-file-video',

            // Compressed formats
            'application/zip' => 'fa fa-file-archive',
            'application/x-rar-compressed' => 'fa fa-file-archive',
            'application/x-tar' => 'fa fa-file-archive',

            // Others
            'application/json' => 'fa fa-file-code',
            'application/xml' => 'fa fa-file-code',
            'text/html' => 'fa fa-file-code',
            'application/javascript' => 'fa fa-file-code',
        ];

        return $icons[$mimeType] ?? 'fa fa-file'; // Default icon for unknown types
    }
}
