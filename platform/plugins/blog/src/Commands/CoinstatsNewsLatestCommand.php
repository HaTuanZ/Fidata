<?php

namespace Botble\Blog\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Botble\Blog\Models\Post;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Support\Facades\Http;

class CoinstatsNewsLatestCommand extends Command {

	public $postRepository;
	public $slugRepository;

	protected $signature = 'blog:coinstats-news-latest';

	protected $description = 'Get latest news list from Coinstats';

	public function __construct(PostInterface $postRepository, SlugInterface $slugRepository)
	{
		parent::__construct();
		$this->postRepository = $postRepository;
		$this->slugRepository = $slugRepository;
	}

	public function handle() {
		$author_id = 8; // Latest - appbotww@gmail.com
		$skip = 0;
		$limit = 20;
		$endpoint = "https://api.coinstats.app/public/v1/news/latest?skip=$skip&limit=$limit";
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $endpoint);
		$statusCode = $response->getStatusCode();
		if($statusCode == 200) {
			$body = json_decode($response->getBody());
			$news = $body->news;
			foreach ($news as $row) {
				$id = $row->id;
				$title = $row->title;
				$slug = \Str::slug($title);

				$feedDate = $row->feedDate;
				$source = $row->source;
				$sourceLink = $row->sourceLink;
				$link = $row->link;
				$isFeatured = isset($row->isFeatured) ? $row->isFeatured : 0;
				$content = (isset($row->content) && $row->content) ? $row->content : "";
				$description = $row->description;;

				$imgURL = $row->imgURL;
				$relatedCoins = $row->relatedCoins ? json_encode($row->relatedCoins) : NULL;
				$coins = $row->coins ? json_encode($row->coins) : NULL;
				$reactionsCount = $row->reactionsCount ? json_encode($row->reactionsCount) : NULL;

				$status = "pending";
				$response = Http::get($imgURL);
				if( $response->successful() ) {
					$status = "published";
				}

				$post_data = array(
					'name'            => $title,
					'slug'            => $slug,
					'custom_slug'     => $slug,
					'author_id'       => $author_id,
					'description'     => $content,
					'content'         => $description,
					'image'           => $imgURL,
					'is_featured'     => $isFeatured,
					'type'            => 'easy',
					'published_at'    => date("Y-m-d H:i:s", $feedDate/1000),
					'status'          => $status,
					'ref_id'          => $id,
					'related_coins'   => $relatedCoins,
					'coins'           => $coins,
					'link'            => $link,
					'source_link'     => $sourceLink,
					'source'          => $source,
					'reactions_count' => $reactionsCount
				);

				$post = Post::where([['ref_id', '=', $id]])->first();
				if($post) {
					$post->update($post_data);
				} else {
					$post = Post::create($post_data);
					//$post = $this->postRepository->createOrUpdate($post_data);

					if($post->status == "published") {
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => 'https://api.cloudflare.com/client/v4/accounts/8a2759ce58896a17b09bb38634029ad4/images/v1',
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS => array('url' => $post->image, 'id' => $post->id),
							CURLOPT_HTTPHEADER => array(
								'Authorization: Bearer yNIkrCx38WM99XAxGl3Jk8KtomUGYq3q3xD9j1Cg'
							),
						));
						$response = curl_exec($curl);
						curl_close($curl);
						$response_decode = json_decode($response);
						if($response_decode->success) {
							$post->update(array("cloudflare_image_id" => $response_decode->result->id));
						}
					}
				}

				$slug_row = $this->slugRepository->getFirstBy(['key' => $slug, 'reference_type' => 'Botble\Blog\Models\Post']);
				if(!$slug_row) {
					$this->slugRepository->createOrUpdate([
						'key'            => $slug,
						'reference_id'   => $post->id,
						'reference_type' => 'Botble\Blog\Models\Post',
					]);
				}

				$post->categories()->sync([12]);
			}
		} else {
			Log::warning($response);
		}
	}

	public function _handle() {
		$skip = 0;
		$limit = 20;
		$toDate = time() * 1000;
		$fromDate = strtotime("-1 day") * 1000;
		$endpoint = "https://api.coinstats.app/public/v1/news?skip=$skip&limit=$limit&toDate=$toDate&fromDate=$fromDate";
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $endpoint);
		$statusCode = $response->getStatusCode();
		if($statusCode == 200) {
			$body = json_decode($response->getBody());
			$news = $body->news;
			foreach ($news as $row) {
				$id = $row->id;
				$title = $row->title;
				$slug = \Str::slug($title);

				$feedDate = $row->feedDate;
				$source = $row->source;
				$sourceLink = $row->sourceLink;
				$link = $row->link;
				$isFeatured = isset($row->isFeatured) ? $row->isFeatured : 0;
				$content = $row->description;
				$description = $source;
				if($sourceLink) {
					$description .= "\n".$sourceLink;
				}
				if($link) {
					$description .= "\n".$link;
				}
				$imgURL = $row->imgURL;
				$relatedCoins = $row->relatedCoins ? json_encode($row->relatedCoins) : NULL;
				$coins = $row->coins ? json_encode($row->coins) : NULL;

				$post_data = array(
					'name'          => $title,
					'custom_slug'   => $slug,
					'author_id'     => 3,
					'description'   => $description,
					'content'       => $content,
					'image'         => $imgURL,
					'is_featured'   => $isFeatured,
					'type'          => 'easy',
					'categories'    => array(8), // Article
					'published_at'  => date("Y-m-d H:i:s", $feedDate/1000),
					'status'        => 'pending',
					'ref_id'        => $id,
					'related_coins' => $relatedCoins,
					'coins'         => $coins,
					'link'          => $link,
					'source_link'   => $sourceLink,
					'source'        => $source
				);

				$post_first = Post::where([['ref_id', '=', $id], ['author_id', '=', 1]])->first();
				if($post_first) {
					$post_first->update($post_data);
				} else {
					Post::create($post_data);
				}
			}
		} else {
			Log::warning($response);
		}
	}
}