<?php

namespace Domain\Services;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Services\FileServiceContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\Services\FileRepositoryContracts;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Auth;

class FileService extends AbstractService implements FileServiceContracts
{
    private AccountServiceContracts $accountService;
    private FileRepositoryContracts $fileRepository;
    private CompanyRepositoryContracts $companyRepository;

    public function __construct(
        AccountServiceContracts $accountService,
        FileRepositoryContracts $fileRepository,
        CompanyRepositoryContracts $companyRepository
    ){
        $this->accountService = $accountService;
        $this->fileRepository = $fileRepository;
        $this->companyRepository = $companyRepository;
        $this->repository = $fileRepository;
    }

    public function listMyFiles($options=[]) {
        return $this->_getAllBy(['autor'=>auth()->user()],$options);
    }

    public function upload(UploadedFile $file) {


        $filesave = "";
        $fileEn = [];
        $fileEn['name'] = $file->getClientOriginalName();
        $fileEn['mime'] = $file->getMimeType();
        $file = $file->store('.');
        $fileEn['storage']='crm';
        $fileEn['patch']=substr($file,2);
        $fileEn['hash']= hash('crc32b',$fileEn['name'].microtime()).substr($file,2,-20);
        $fileEn['autorId'] = Auth::user()->getId();
        $fileEn['active']=true;
        $fileEn['size'] = Storage::size($fileEn['patch']);
        $filesave = $this->fileRepository->loadNew($fileEn);

        $filesave = $this->fileRepository->save($filesave);

        return $filesave;

    }

    public function _getById($hash) {
            return $this->getHashFile($hash);
    }
    public function deleteHashFile($hash) {
        $file = $this->getHashFile($hash);

        return $this->fileRepository->deleteById($file->getId());
    }

    public function setHashObjectFile($hash,$object){
        $file = $this->getHashInfoFile($hash);
        if ($file ) {
            $file->getObjects()->clear();
        }

        if ($file && ! $file->getObjects()->contains($object)) {

            $file->addObject($object);
            $this->fileRepository->save($file);
        }

        return $file;
    }

    public function getHashInfoFile($hash){
        $file = $this->fileRepository->findBy(['hash'=>$hash]);

        if (!$file) {
            return null;
        }
        $url = Storage::url($file->getPatch());

        return $file;
    }

    public function getOne($hash) {
        if (is_string($hash)){
            return $this->getHashFile($hash);
        }
    }

    public function publicFile($hash)
    {
        $file = $this->fileRepository->getFileHash($hash);
        return $this->fileRepository->update($file,['public'=>$file->getHash(),'fileStore'=>$fileStore]);
    }
    public function getHashFile(string $hash=null) {
        if (!$hash) {
            return null;
        }

        $hash = trim($hash);
        $file = $this->fileRepository->getFileHash($hash);

        if (!$file) {
            return null;
        }

        return $file;
    }
     public function setObjectHashFile($hash,$Object) {

        $file = $this->getHashFile($hash);

        if ($file){
            $this->fileRepository->update($file,['objects'=>$Object->getid()]);
        }

    }
}
