<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;
    protected $fillable = ['partner_id','product_type_id','title','subtitle','slug','product_thumbnail','star_rating','status','created_at','modified_at'];
    
    public function tour()
    {
        return $this->hasOne(Tour::Class,'product_id');
    }

    public function souvenir()
    {
        return $this->hasOne(Souvenir::Class,'product_id');
    }

    public function culinary()
    {
        return $this->hasOne(Culinary::Class,'product_id');
    }

    public function hotel()
    {
        return $this->hasOne(Hotel::Class,'product_id');
    }

    public function rentcar()
    {
        return $this->hasOne(RentCar::Class,'product_id');
    }

    public function producttag()
    {
        return $this->hasMany(ProductTag::Class,'product_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::Class,'partner_id');
    }

    public function producttype()
    {
        return $this->belongsTo(ProductType::Class,'product_type_id');
    }

    public function getProduct($id, $slug)
    {
    	$prefix = DB::getTablePrefix();
		$select = [
			'products.*',
			'product_details.product_detail_id',
			'product_types.template AS product_template',
			'partners.company_name',
			'partners.fullname AS partner_name',
			'public_price',
			DB::raw('AVG('.$prefix.'reviews.star_review) AS review_value'),
			DB::raw('COUNT('.$prefix.'reviews.review_id) AS review_count')
		];

		$product = Product::join('product_types', 'product_types.product_type_id', 'products.product_type_id')
			->join('partners', 'partners.partner_id', 'products.partner_id')
			->leftjoin('product_details','product_details.product_id','products.product_type_id')
			->leftjoin('product_prices','product_prices.product_id','products.product_id')
			->leftjoin('reviews','reviews.content_id', 'products.product_id')
			->select($select);
		$products = $product->where('products.product_id', $id)
			->where('products.slug', $slug)
			->groupBy('products.product_id')
			->first();

		return $products;
    }

    public function getRecomendation($slug, $limit)
    {
    	switch($slug)
    	{
    		case 'tour':
    			$products = $this->getTour($limit);
    			break;
    		case 'souvenir':
    			$products = $this->getSouvenir($limit);
    			break;
    		case 'kuliner':
    			$products = $this->getKuliner($limit);
    			break;
    		case 'akomodasi':
    			$products = $this->getAkomodasi($limit);
    			break;
    		case 'transportasi':
    			$products = $this->getTransportasi($limit);
    			break;
    		default:
    			$products = [];
    	}

    	return $products;
    }

    private function getTour($limit)
    {
    	$products = Product::join('tours', 'tours.product_id', 'products.product_id')
    		->select([
    			'products.product_id',
    			'products.product_thumbnail',
    			'products.title',
    			'products.slug',
    			DB::raw('(SELECT COUNT(tour_schedule_id) FROM '.DB::getTablePrefix().'tour_schedules where tour_id= '.DB::getTablePrefix().'tours.tour_id AND schedule_date > curdate()) AS product_schedules')
    		])
    		->having('product_schedules','>', 0)
			->where('products.product_type_id',1)
    		->where('products.status', 1)
    		->limit($limit)
    		->inRandomOrder()
    		->get();

    	return $products;
    }

    private function getSouvenir($limit)
    {
    	$products = Product::where('product_type_id',2)->where('status', 1)
    		->limit($limit)
    		->inRandomOrder()
    		->get();

    	return $products;
    }

    private function getKuliner($limit)
    {
    	$products = Product::where('product_type_id',3)->where('status', 1)
    		->limit($limit)
    		->inRandomOrder()
    		->get();

    	return $products;
    }

    private function getAkomodasi($limit)
    {
    	$products = Product::join('partners', 'partners.partner_id', 'products.partner_id')
    		->select([
    			'partners.partner_id AS product_id',
    			'partners.company_thumbnail AS product_thumbnail',
    			'partners.company_name AS title',
    			'partners.slug',
			])
			->distinct()
			->where('product_type_id',4)
    		->where('products.status', 1)
    		->limit($limit)
    		->inRandomOrder()
    		->get();

    	return $products;
    }

    private function getTransportasi($limit)
    {
    	$productdata = Product::join('rent_cars', 'rent_cars.product_id', 'products.product_id')
			->join('car_brands', 'rent_cars.car_brand_id', 'car_brands.car_brand_id')
			->join('car_models', 'rent_cars.car_model_id', 'car_models.car_model_id')
    		->select([
    			'car_models.car_model_id',
				'car_brands.name as car_brand_name',
				'car_models.name as car_model_name',
    			'car_models.car_image'
    		])
			->where('product_type_id',5)
			->where('products.status', 1)
			->distinct()
    		->limit($limit)
    		->inRandomOrder()
    		->get();

		$products = array();
		foreach($productdata as $row){
			$nestedData = array();
			$nestedData['product_id'] = $row->car_model_id;
			$nestedData['product_thumbnail'] = $row->car_image;
			$nestedData['title'] = $row->car_brand_name . ' ' . $row->car_model_name;
			$nestedData['slug'] = strtolower($row->car_brand_name . '-' . $row->car_model_name);
			$products[] = $nestedData;
		}
    	return $products;
    }
}