<?php


namespace App\Api\Front;


use App\Model\Article as ArticleModel;

class Article extends BaseController
{
    public function lists()
    {
        $page = \Flight::request()->query->page;
        $pageSize = \Flight::request()->query->pageSize;
        $page = intval($page) ? intval($page) : 1;
        $pageSize = intval($pageSize) ? intval($pageSize) : 10;
        $article = new ArticleModel();
        $list = $article->articleList([], $page, $pageSize);
        $data['page']['current'] = $page;
        $data['page']['size'] = $pageSize;
        $data['page']['total'] = $list['count'];
        $data['page']['page_total'] = ceil($list['count'] / $pageSize);

        if(empty($list['data']))
        {
            $this->jsonError(401, $list['msg'], []);
        }
        $data['list'] = $list['data'];
        $this->jsonSuccess($data);
    }
}