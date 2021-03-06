<?php

namespace Modules\Article\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Article\Entities\Article;
use Modules\Article\Entities\ArticleTrans;

class ArticleResource extends JsonResource
{
    public function __construct($resource)
    {
        if ($resource instanceof ArticleTrans) {

            $resource->date = $resource->getArticle->date;
            $resource->date_to = $resource->getArticle->date_to;
            $resource->image = $resource->getArticle->image;
        }

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'desc'              => $this->generateMiniDescription(),
            'description'       => "<div>$this->description</div>",
            'imgObj'            => $this->imgObj(),
            'srcset'            => $this->srcset(),
            'link'              => $this->generateLink(),
            'date'              => $this->date,
            'show_date'         => $this->date->format('d.m.Y'),
            'date_to'           => $this->date_to,
            'promo_finish_date' => $this->date_to && $this->date_to->isFuture() ? $this->date_to->timestamp : null,
            'meta_title'         => $this->generateMeta('meta_title', ['title']),
            'meta_description'   => $this->generateMeta('meta_description', ['description', 'short_desc']),
            'meta_keywords'      => $this->generateMeta('meta_keywords')
        ];
    }

    private function imgObj($size = 'xs')
    {
        $data = [];
        if ($this->image) {
            $data = [
                'src'     => asset('storage/' . Article::FOLDER_IMG . '/' . $size . '/' . $this->image),
                'loading' => asset('storage/' . Article::FOLDER_IMG . '/50/' . $this->image)
            ];
        }

        return $data;
    }

    private function generateLink()
    {
        $params = [
            count(config('app.locales')) > 1 ? config('app.locale') : null,
            array_key_exists($this->type, cache('urls_pages_' . config('app.locale_id'))) ? cache('urls_pages_' . config('app.locale_id'))[$this->type] : '',
            $this->slug
        ];
        return route('pages', $params, false);
    }

    private function srcset()
    {
        $srcset = [];
        if ($this->image) {
            foreach (Article::getSizes() as $size => $sizes) {
                if ($size != 50)
                    $srcset[] = asset('storage/' . Article::FOLDER_IMG . '/' . $size . '/' . $this->image) . ' ' . $sizes['width'] . 'w';
            }
        }
        return $srcset;
    }

    private function generateMiniDescription()
    {
        $description = null;
        if ($this->short_desc)
            $description = $this->short_desc;
        else
            $description = $this->description;

        return '<div>' . html_entity_decode(\Str::limit(strip_tags($description), 160)) . '</div>';
    }

    private function generateMeta(string $key, array $keys = [], int $length = 140)
    {
        $response = null;
        if (!empty($this->{$key})) {
            $response = $this->clearString($this->{$key}, $length);
        } elseif ($keys) {
            foreach ($keys as $keyName) {
                if (!empty($this->{$keyName})) {
                    $response = $this->clearString($this->{$keyName}, $length);
                    break;
                }
            }
        }

        return $response;
    }

    private function clearString(string $string, int $limit)
    {
        $string = html_entity_decode(strip_tags($string));
        $string = \Str::limit($string, $limit, '');

        return preg_replace('!\s+!', ' ', trim($string));
    }
}
