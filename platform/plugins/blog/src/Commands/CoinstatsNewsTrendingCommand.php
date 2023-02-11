<?php

namespace Botble\Blog\Commands;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Botble\Blog\Models\Post;
use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Support\Facades\Http;

class CoinstatsNewsTrendingCommand extends Command {

	public $postRepository;
	public $slugRepository;

	protected $signature = 'blog:coinstats-news-trending';

	protected $description = 'Get news trending list from Coinstats';

	public function __construct(PostInterface $postRepository, SlugInterface $slugRepository)
	{
		parent::__construct();
		$this->postRepository = $postRepository;
		$this->slugRepository = $slugRepository;
	}

	public function handle() {
		$author_id = 6;
		$skip = 0;
		$limit = 20;
		$endpoint = "https://api.coinstats.app/public/v1/news/trending?skip=$skip&limit=$limit";
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
					'reactions_count' => $reactionsCount,
				);

				$post = Post::where([['ref_id', '=', $id]])->first();
				if($post) {
					$post->update($post_data);
				} else {
					$post = Post::create($post_data);
				}

				$slug_row = $this->slugRepository->getFirstBy(['key' => $slug, 'reference_type' => 'Botble\Blog\Models\Post']);
				if(!$slug_row) {
					$this->slugRepository->createOrUpdate([
						'key'            => $slug,
						'reference_id'   => $post->id,
						'reference_type' => 'Botble\Blog\Models\Post',
					]);
				}

				$post->categories()->sync([11]);
			}
		} else {
			Log::warning($response);
		}
	}
}