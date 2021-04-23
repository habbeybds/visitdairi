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
use App\Models\Review;
use DB;

class ReviewController extends BaseController
{

	protected $_config;
    protected $_func;
    
    public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
    }
    
    public function getReviews(Request $request)
    {
        $response['status'] = 'failed';
        $response['data'] = [];
        $response['more'] = false;

        $productId = $request->product_id;
        $productType = $request->product_type;
        $limit = $request->limit ? $request->limit : 3;
        $page = $request->page ? $request->page : 0;

        $reviews = Review::join('trans', 'trans.trans_id', 'reviews.content_id')
            ->join('trans_det', 'trans_det.trans_id', 'trans.trans_id')
            ->join('customers', 'customers.customer_id', 'reviews.customer_id')
            ->select([
                'customers.first_name',
                'customers.last_name',
                'reviews.comments',
                'reviews.star_review',
                'reviews.created_at'
            ])
            ->distinct('trans.trans_id')
            ->where('reviews.content_type', 'product')
            ->where('reviews.status', 1);
        
        if($request->product_type == 'tour') {
           //$reviews->where('trans_det.product_id', $productId);
        }
        elseif($request->product_type == 'souvenir') {
            $reviews->where('trans_det.product_id', $productId);
        }
        elseif($request->product_type == 'kuliner') {
            $reviews->where('trans_det.product_id', $productId);
        }
        elseif($request->product_type == 'akomodasi') {
            $reviews->where('trans_det.product_id', $productId);
        }
        elseif($request->product_type == 'transportasi') {
            $reviews->where('trans_det.product_id', $productId);
        }

        $reviews = $reviews->orderBy('reviews.created_at','desc')
            ->paginate($limit);
        if($reviews->count() > 0) 
        {
            $data = [];
            foreach($reviews as $review) 
            {
                $data[] = [
                    'name' => implode(' ', [$review->first_name, $review->last_name]),
                    'message' => $review->comments,
                    'star' => $review->star_review,
                    'created_at' => date("d M Y H:i:s", strtotime($review->created_at))
                ];
            }
            $response['status'] = 'success';
            $response['data'] = $data;
            $response['more'] = $reviews->hasMorePages();
            $response['next_page'] = $reviews->nextPageUrl();
        }
        return $response;
    }

    public function review()
    {
        $data = [];
        $comments = [
            'Terima kasih Visit Dairi, perjalanannya menyenangkan dan pemandunya sangat ramah.',
            'Waw, gak kecewa deh untuk harga segini. Penyambutan dan pelayanannya luaaar biasaa.',
            'Alhamdulillah, pertama kali ke Dairi dan mendapatkan pengalaman terbaik dari Visit Dairi.',
            'Restorannya nyaman banget, menunga banyak dan bervariasi.',
            'Makanannya enak-enak dan minumannya seger banget apalagi kopi dan coklatnya, wow banget deh.',
            'Harganya terjangkau, menunga bervariasi dan rata-rata yang saya pesen enak semuaa, Terima kasih visit dairi atas rekomendasinya.',
            'Penginapannya nyaman banget dan pelayanannya luar biasa.',
            'Pemandangan dari dalam kamar sangat indah dan bersih banget penginapannya.',
            'Keren visit dairi atas rekomendasinya.',
            'Pengemudinya ramah banget dan tau tempat-tempat bagus.',
            'Sewanya murah banget dan mobilnya sangat harum dan bersih',
            'Pengemudinya mengendarai mobil dengan enak dan ramah banget.'
        ];

        foreach($comments as $key=>$comment) {
            $uuid = Uuid::uuid4()->toString();
            $data[] = [
                'review_id' => $uuid,
                'customer_id' => 15,
                'content_type' => 'product',
                'content_id' => 1,
                'comments' => $comment,
                'star_review' => 5,
                'ip' => '127.0.0.1',
                'host' => '',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        Review::insert($data);
        return $data;
    }

}