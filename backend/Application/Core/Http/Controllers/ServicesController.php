<?php

namespace Core\Http\Controllers;
use Illuminate\Http\Request;
use Domain\Contracts\Services\NewsServiceContracts;
use Domain\Contracts\Services\FileServiceContracts;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\URL;
use Infrastructure\Util\FileIconHelper;

class ServicesController extends Controller
{
    private FileServiceContracts $fileService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileServiceContracts $fileService)
    {
        $this->fileService = $fileService;
    }

    public function actionFileInfo(Request $request,$hash = "")
    {
        if (!$hash) {
            $this->validate($request, [
                'hash' => 'required|string',
            ]);
        }
        $infoFile = $this->fileService->getHashInfoFile($request->hash);
        return $this->sendResponse($infoFile);
    }

    public function actionFileDelete(Request $request)
    {
        $this->validate($request, [
            'hash' => 'required|string',
        ]);
        if ($this->fileService->deleteHashFile($request->hash)) {
            return $this->sendResponse('true');
        }  else {
            return $this->sendResponse('false');
        }

    }

    public function actionFileGet($hash) {
        $file = $this->fileService->getHashFile($hash);
        $mimeType = Storage::mimeType($file->getPatch());
        if (Storage::exists($file->getPatch())) {

            return Storage::readStream($file->getPatch());
        } else {
            return abort(404, 'Файл не найден');
        }


    }

    public function actionFileGetJson(Request $request) {
        $this->validate($request, [
            'hash' => 'required|string',
        ]);
        $file = $this->fileService->getHashFile($request->hash);
        if ($file) {
            $url = Storage::url($file->getPatch());
            return Storage::get($file->getPatch(),$file->getName());
        } else {
            return $this->sendResponse('No file ['.$request->hash.'] found');
        }
    }

    public function listMyFilesCloud(Request $request)
    {

        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $files = $this->fileService->listMyFiles($request->get('options'));

        return  $this->sendResponse($files['data'],$files['options']);

    }

    public function loadFilePublic($hash, Request $request)
    {
        $request->merge(['file' => $hash]);
        $this->validate($request, [
            'file' => 'required|string|exists:Domain\Entities\Services\Files,public',
        ]);

        return $this->actionFileGet($hash);
    }
    public function viewInfoFilePage($hash, Request $request) {

        //$request->request->add(['hash' => $hash]);
        $request->merge(['file' => $hash]);
        $this->validate($request, [
            'file' => 'required|string|exists:Domain\Entities\Services\Files,public',
        ]);

        $file = $this->fileService->getHashFile($hash);
        $fileStore = Storage::url($file->getPatch());
        $mimeType = Storage::mimeType($file->getPatch());
        $iconClass = FileIconHelper::getFileIcon($mimeType);


        return view('files.info', ['file'=>$file, 'iconClass'=>$iconClass]);
    }

    public function actionFilePublic(Request $request)
    {
        $this->validate($request, [
            'hash' => 'required|string|exists:Domain\Entities\Services\Files,hash',
        ]);
        return $this->sendResponse($this->fileService->publicFile($request->get('hash')));

    }
    public function actionFileLoad(Request $request) {
        $files = new Collection();
        foreach ($request->file() as $file) {
           $files->add($this->fileService->upload($file));

        }

        return $this->sendResponse($files->all());
    }


}
