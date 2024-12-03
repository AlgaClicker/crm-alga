<?php

namespace Domain\Services;


use Domain\Contracts\Repository\NewsRepositoryContracts;
use Domain\Contracts\Repository\NewsCommentsRepositoryContracts;
use Domain\Contracts\Services\NewsServiceContracts;
use Core\Events\NewsEvent;
use Domain\Entities\News\Comments;
use Auth;
class NewsService extends AbstractService implements NewsServiceContracts
{
    private NewsRepositoryContracts $newsRepository;
    private NewsCommentsRepositoryContracts $newsCommentsRepository;

    public function __construct(
        NewsRepositoryContracts $newsRepository,
        NewsCommentsRepositoryContracts $newsCommentsRepository
    ){
        $this->newsRepository = $newsRepository;
        $this->newsCommentsRepository = $newsCommentsRepository;
        parent::__construct($newsRepository);
    }

    public function create($arrayAtrr) {
        $news = $this->newsRepository->create($arrayAtrr);
        event(new NewsEvent($news));

        return $news;
    }

    public function addNewsComent($idNews,$request) {
        $news = $this->newsRepository->findOne($idNews);
        event(new NewsEvent($news));
        return $this->newsRepository->createCommnets($news,$request);
    }

    public function getAll()
    {
        return $this->_getAll();
    }

    public function getAllCommnets($idNews)
    {
        return $this->newsRepository->findAllCommnets($idNews);
    }

    public function getOne($id) {
        $news = $this->newsRepository->findOne($id);

        return $news;
    }
    public function deleteById($id)
    {
        return $this->newsRepository->deleteById($id);
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
