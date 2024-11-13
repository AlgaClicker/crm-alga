<?php
namespace Domain\Contracts\Services;
use Illuminate\Http\UploadedFile;

interface FileServiceContracts{
    public function getHashFile(string $hash);
    public function getHashInfoFile(string $hash);
    public function upload(UploadedFile $file);
}
