<?php


namespace App\Api\Backend\Controller;

use App\Model\Article as ArticleModel;
use App\Model\ArticleContent;

/**
 * Class Article
 * @package App\Api\Backend\Controller
 */
class Article  extends BaseController
{
    public function wechat_list()
    {
        $app = getWechatApp();
        $material = $app->material;
        $page = \Flight::request()->query->page;
        $pageSize = \Flight::request()->query->pageSize;
        $page = intval($page) ? intval($page) : 1;
        $pageSize = intval($pageSize) ? intval($pageSize) : 10;
        $start = ($page - 1) * $pageSize;
        $result = $material->list('news', $start, $pageSize);
        if(empty($result['item']))
        {
            $this->jsonError(401, $result['msg'], []);
        }
        $list = [];
        $data['page']['current'] = $page;
        $data['page']['size'] = $pageSize;
        $data['page']['total'] = $result['total_count'];
        $data['page']['page_total'] = ceil($result['total_count'] / $pageSize);
        foreach($result['item'] as $item)
        {
            $tmp['title'] = $item['content']['news_item'][0]['title'];
            $tmp['author'] = $item['content']['news_item'][0]['author'];
            $tmp['digest'] = $item['content']['news_item'][0]['digest'];
            $tmp['create_time'] = $item['content']['create_time'] * 1000;
            $tmp['update_time'] = $item['content']['update_time'] * 1000;
            $tmp['media_id'] = $item['media_id'];
            $list[] = $tmp;
        }
        $data['list'] = $list;
        $this->jsonSuccess($data);
    }

    public function wechat_article_info()
    {
        $id = \Flight::request()->query->id;
        if(empty($id))
        {
            $this->jsonError(4001, '参数错误');
        }
        $app = getWechatApp();
        $material = $app->material;
        $result = $material->get($id);
        $data = $result['news_item'][0];
        $data['media_id'] = $id;
        $data['create_time'] = $result['create_time'] * 1000;
        $data['update_time'] = $result['update_time'] * 1000;
        $this->jsonSuccess(['info' => $data]);
    }

    public function local_list()
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

    /**
     * 导入文章
     */
    public function import_wechat_article()
    {
        $params = \Flight::request()->data;
        $media_id = $params['media_id'];
        if(empty($media_id))
        {
            $this->jsonError(4013, '抓取远程文章失败', []);
        }
        $app = getWechatApp();
        $material = $app->material;
        $result = $material->get($media_id);
        if(empty($result))
        {
            $this->jsonError(4014, '抓取远程文章失败', []);
        }
        $data = $result['news_item'][0];
        $article = new ArticleModel();
        $result = $article->saveArticle(
            [
                'title' => $data['title'],
                'author' => $data['author'],
                'digest' => $data['digest'],
                'media_id' => $media_id,
                'thumb_url' => $data['thumb_url']
            ]);
        if(empty($result['data']))
        {
            $this->jsonError($result['code'], $result['msg']);
        }
        $content = new ArticleContent();
        $content->saveContent($result['data'], $data['content']);
        $this->jsonSuccess([]);
    }
}