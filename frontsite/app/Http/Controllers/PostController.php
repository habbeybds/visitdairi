<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Posting;
use App\Models\PostingCategory;
use DB;

class PostController extends BaseController
{

	protected $_config;
	protected $_func;

	protected function postingDate($stringDate)
	{
		$DaysID = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
		$MonthID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

		$date = strtotime($stringDate);
		$stringDate = [
			$DaysID[date('w',$date)].',',
			date('d',$date),
			$MonthID[date('n',$date)-1],
			date('Y',$date)
		];
		return implode(' ', $stringDate);
	}
	
	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}

	public function detail($postingSlug, PostingCategory $cat)
	{
		// get config
		$config = $this->_config->get();  
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$postId = strtok($postingSlug, '-');
		$post = Posting::where('post_id', $postId)
			->where('published', 1)
			->first();

		// overwrite meta keyword
		if(!empty($post->meta_keyword))
		{
			$config['sitemetakeyword'] = $post->meta_keyword;
		}	

		// overwrite meta description
		if(!empty($post->meta_description))
		{
			$config['sitemetadesc'] = $post->meta_description;
		}

		if($post)
		{
			$data['categories'] = $cat->getSitebar();
			$post['post_date'] = $this->postingDate($post['created_at']);
			$data['post'] = $post;

			// update viewed stats
			Posting::where('post_id', $postId)->increment('viewed');

			return view('posts.detail', $data);
		}
		return view('errors.404', $data);
	}

	public function listByCategory($categoryId, $categorySlug)
	{
		// get config
		$config = $this->_config->get();  
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$postCategory = PostingCategory::where('post_category_id', $categoryId)->first();
		if($postCategory)
		{
			$data['category'] = $postCategory;
			return view('posts.category', $data);
		}
		return view('errors.404', $data);
	}

	public function ajaxGetLatest(Request $request)
	{
		$posts = Posting::leftjoin('post_categories AS c', 'c.post_category_id', 'posts.post_category_id')
			->where('posts.published', 1)
			->orderBy('posts.created_at', 'DESC')
			->limit(3)
			->select([
				'posts.post_id AS id',
				'posts.post_slug AS slug',
				'posts.post_title AS title',
				'posts.image',
				'posts.post_content',
				'c.category_name',
				'c.category_slug',
				'posts.created_at'
			])
			->get();

		$result = [];
		if(sizeof($posts) > 0)
		{
			foreach($posts as $key=>$post)
			{	

				$content = substr(strip_tags($post->post_content), 0, 150);

				// posting date
				$datetime = strtotime($post->created_at);
				$date = '';
				if(date('Y-m-d', $datetime) == date('Y-m-d')) {
					$date = 'Hari ini ' . date('H:i');
				} elseif(date('Y-m-d', strtotime('-1 days')) == date('Y-m-d', $datetime)) {
					$date = 'Kemarin ' . date('H:i');
				} else {
					$date = $this->_func->dateIDFormat(date('Y-m-d', $datetime));
				}
			
				$result[] = [
					'title' => $post->title,
					'description' => $content,
					'image' => $post->image,
					'slug' => $post->id . '-' . $post->slug, 
					'date' => $date
				];
			}
		}
		return $result;
	}

	public function ajaxGetPopuler(Request $request)
	{
		$posts = Posting::leftjoin('post_categories AS c', 'c.post_category_id', 'posts.post_category_id')
			->where('published', 1)
			->orderBy('viewed', 'DESC')
			->limit(3)
			->select([
				'posts.post_id AS id',
				'posts.post_slug AS slug',
				'posts.post_title AS title',
				'posts.post_content',
				'posts.image',
				'c.category_name',
				'c.category_slug',
				'posts.created_at'
			])
			->get();

		$result = [];
		if($posts)
		{
			foreach($posts as $key=>$post)
			{	

				$content = substr($post->post_content, 0, 150);

				// posting date
				$datetime = strtotime($post->created_at);
				$date = '';
				if(date('Y-m-d', $datetime) == date('Y-m-d'))
				{
					$date = 'Hari ini ' . date('H:i');
				} elseif(date('Y-m-d', strtotime('-1 days')) == date('Y-m-d', $datetime)) {
					$date = 'Kemarin ' . date('H:i');
				} else {
					$date = $this->_func->dateIDFormat(date('Y-m-d', $datetime));
				}
			
				$result[] = [
					'title' => $post->title,
					'description' => $content,
					'image' => $post->image,
					'slug' => $post->id . '-' . $post->slug, 
					'date' => $date
				];
			}
		}
		return $result;
	}

	public function ajaxGetAllPost(Request $request)
	{
		$baseImage = config('constants.UPLOAD_PATH');
		$posts = Posting::leftjoin('post_categories AS c', 'c.post_category_id', 'posts.post_category_id')
			->select([
				'posts.post_id AS id',
				'posts.post_slug AS slug',
				'posts.post_title AS title',
				'posts.post_content',
				'c.category_name',
				'c.category_slug',
				'posts.created_at',
				DB::raw('CONCAT("'.$baseImage.'", image) AS image')
			])
			->where('published',1);
		if($request->category) 
		{
			$posts->where('c.post_category_id', $request->category);
		}
	
		$posts = $posts->orderBy('posts.created_at', 'DESC')
			->paginate(9);

		$posts = $posts->toArray();
		$func = $this->_func;
		$posts['data'] = array_map(function($item) use ($func) {
			$item['post_content'] = substr(strip_tags($item['post_content']), 0, 255);
			$item['date'] = $func->dateIDFormat(date('Y-m-d', strtotime($item['created_at'])));
			return $item;
		}, $posts['data']);
		return $posts;
	}
}
