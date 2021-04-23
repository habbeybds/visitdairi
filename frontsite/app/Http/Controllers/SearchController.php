<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Helpers\FunctionsHelper;
use App\Repositories\CustomerRepository;
use App\Repositories\ConfigRepository;
use App\Models\Destination;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Posting;
use App\Models\LiteSearch;
use DB;

class SearchController extends BaseController
{

	protected $_config;
	protected $_func;

	const PRODUCT_TOUR = 1;
	const PRODUCT_SOUVENIR = 2;
	
	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}

	protected function splitPhrase($keyword)
	{
		$phrases = [];
		$keyword = str_replace(['""',"''"], '', $keyword);
		if(preg_match_all('/"([^"]+)"/', $keyword, $matches))
		{
			foreach($matches[1] as $key=>$match) 
			{
				$keyword = str_replace($matches[0][$key], '', $keyword);
				$phrases[] = $match;
			}
		}
		return [$phrases,$keyword];
	}

	protected function removeIgnoredWords($words)
	{
		$ignoredWords = config('constants.SEARCH_IGNORED');
		$words = preg_replace("/(\W|^)(".$ignoredWords.")(\W|$)/i", ' ', $words);
		return $words; 
	}

	protected function removeQuote($keyword)
	{
		$keyword = str_replace(['"',"'"], '', $keyword);
		return $keyword;
	}

	protected function splitWord($keyword)
	{
		$keyword = trim($keyword);
		if($words = preg_split('/\s+/', $keyword)) 
		{
			return $words;	
		}
		return [];
	}

	protected function mergeKeywords($words, $phrases)
	{
		$keywords = array_merge($words, $phrases);
		return $keywords;
		$regexp = '';
		$regexp = implode('|', $words).'|'.implode('|', $phrases);
		$regexp = trim($regexp, '|');
		return $regexp;
	}

	protected function highlight($word, $text)
	{
		$word_to_highlight = substr($text, stripos($text, $word), strlen($word));
	    $text = str_ireplace($word, "<span style='background-color:#b6000c;color: #fff;'>".$word_to_highlight."</span>", $text, $counter);
	    return [$counter, $text];
	}

	public function index(Request $request)
	{

		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		/////////////////////
		if($request->k != ''){
			$keyword = $request->k;
			$data['keywords'] = $keyword;
			// split phrases
			list($phrases, $keyword) = $this->splitPhrase($keyword);
	
			// remove quote
			$keyword = $this->removeIgnoredWords($keyword);
			$keyword = $this->removeQuote($keyword);
	
			// split words
			$words = $this->splitWord($keyword);
	
			// merge words and phrases
			$keywords = $this->mergeKeywords($words, $phrases);
	
			// build regexp
			$regexp = implode('|', $keywords);
			$regexp = trim($regexp, '|');
	
			$results = LiteSearch::whereRaw('content_descs REGEXP "'.$regexp.'" and content_status=1')
				->orderBy('counter', 'DESC')
				->get();
	
			$searchResults = [];
			foreach($results as $res)
			{
				$searchResults[$res->content_type][] = $res;
			}
	
			$data['searchResults'] = $searchResults;
		} else {
			$data['keywords'] = '';
			$data['searchResults'] = '';
		}
		
		return view('search.index', $data);
	}

	private function buildData($route, $type, $title, $category, $content)
	{
		$uuid = Uuid::uuid4()->toString();
		$data = [
			'search_id' => $uuid,
			'content_route' => $route,
			'content_type' => $type,
			'content_title' => $title,
			'content_category' => $category,
			'content_descs' => $content,
			'counter' => 0,
		];
		return $data;
	}

	public function buildContent()
	{

		// build content destination
		$destinations = Destination::where('published', 1)->get();
		if(sizeof($destinations)>0)
		{
			$data = [];
			LiteSearch::where('content_type', 'Destinasi')->delete();
			foreach($destinations as $des) {
				$route = 'destination/' . $des->destination_id . '-' . $des->slug;
				$content = $des->name . '##' . $des->title . '##' . strip_tags($des->content);
				$title = $des->name;
				if($title != $des->title && strlen($title) < 20)
				{
					$title = $title . ' - ' . $des->title; 
				}
				$data[] = $this->buildData($route, 'Destinasi', $title, 'Destinasi', $content);
			}
			LiteSearch::insert($data);
		}

		// build content product
		// Product Tour
		$tours = Product::join('tours', 'tours.product_id', 'products.product_id')
			->select([
				'products.product_id',
				'products.slug',
				'products.title',
				'products.subtitle',
				'tours.description'
			])
			->where('products.product_type_id', static::PRODUCT_TOUR)
			->where('products.status', 1)
			->get();
		if(sizeof($tours)>0)
		{
			$data = [];
			LiteSearch::where('content_type', 'Produk')->where('content_category', 'Tour')->delete();
			foreach($tours as $tour) {
				$route = 'tour/' . $tour->product_id . '-' . $tour->slug;
				$content = $tour->title . '##' . $tour->subtitle . '##' . strip_tags($tour->description);
				$title = $tour->title . ' ' . $tour->subtitle;
				$data[] = $this->buildData($route, 'Produk', $title, 'Tour', $content);
			}
			LiteSearch::insert($data);
		}

		// Product Souvenir
		$souvenirs = Product::join('souvenirs','souvenirs.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.slug',
				'products.title',
				'products.subtitle',
				'souvenirs.souvenir_description'
			])
			->where('products.product_type_id', static::PRODUCT_SOUVENIR)
			->where('products.status', 1)
			->get();
		if(sizeof($souvenirs)>0)
		{
			$data = [];
			LiteSearch::where('content_type', 'Produk')->where('content_category', 'Souvenir')->delete();
			foreach($souvenirs as $souvenir) {
				$route = 'souvenir/' . $souvenir->product_id . '-' . $souvenir->slug;
				$content = $souvenir->title . '##' . $souvenir->subtitle . '##' . strip_tags($souvenir->souvenir_description);
				$title = trim($souvenir->title . ' ' . $souvenir->subtitle);
				$data[] = $this->buildData($route, 'Produk', $title, 'Souvenir', $content);
			}
			LiteSearch::insert($data);
		}

		// Product Kuliner

		// Product Akomodasi

		// Product Transportasi

		// build content posting
		$posts = Posting::join('post_categories', 'post_categories.post_category_id', 'posts.post_category_id')
			->select([
				'posts.*',
				'post_categories.category_name',
				'post_categories.category_slug'
			])
			->where('published', 1)
			->get();
		if(sizeof($posts)>0)
		{
			$data = [];
			LiteSearch::where('content_type', 'Kabar Dairi')->delete();
			foreach($posts as $post) {
				$route = 'post/' . $post->post_id . '-' . $post->post_slug;
				$highlight = !empty($post->post_highlight) ? $post->post_highlight : '';
				$content = $post->post_title . '##' . $highlight . '##' . strip_tags($post->post_content);
				$title = $post->post_title;
				$data[] = $this->buildData($route, 'Kabar Dairi', $title, $post->category_name, $content);
			}
			LiteSearch::insert($data);
		}

		return $data;
	}

	public function build()
	{
		$data = [];
		//return false;
		// Destination Contents
		$prodTour = LiteSearch::get();

		foreach($prodTour as $p) {
			$content = explode('##', $p->content_descs);
			LiteSearch::where('search_id',$p->search_id)->update(['content_title'=>reset($content)]);
		}

		

		//return $data;

		//LiteSearch::insert($data);
		return $data;
	}

	public function buildSearchKey() 
	{

	}

	public function getAjaxSearch(Request $request)
	{
		$keyword = $request->keyword;
		$param = $request->post('param');
		parse_str($param, $output);
	}
}
