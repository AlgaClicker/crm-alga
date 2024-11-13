<?php

namespace Core\Http\Controllers;
use Illuminate\Http\Request;
use Domain\Contracts\Services\NewsServiceContracts;

use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    private NewsServiceContracts $newsService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NewsServiceContracts $newsService)
    {
        $this->newsService = $newsService;
        //
    }

    public function actionIndex(Request $request)
    {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $news = $this->newsService->_getAllBy([],$request->get('options'));
        return  $this->sendResponse($news['data'],$news['options']);

    }

    public function actionCreateNews(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'announcement' => 'required'
        ]);

        $credentials =$request->all();

        $news = $this->newsService->create($credentials);

        return  $this->sendResponse($news);
    }

    public function actionShowNews($idNews)
    {
       return $this->sendResponse($this->newsService->getOne($idNews));
    }

    public function actionUpdateNews($idNews)
    {

    }

    public function actionShowNewsComments($idNews)
    {
        $comments = $this->newsService->getAllCommnets($idNews);
        return  $this->sendResponse($comments);

    }

    public function actionDeleteNews($idNews)
    {
        return $this->sendResponse($this->newsService->deleteById($idNews));
    }

    public function actionCreateComments($idNews,Request $request)
    {
        $request = $request->all();

        $news = $this->newsService->addNewsComent($idNews,$request);
        return  $this->sendResponse($news);
    }

    public function actionUpdateComments($idComments,Request $request)
    {

    }

    public function actionDeleteComments($idComment)
    {

    }


}
