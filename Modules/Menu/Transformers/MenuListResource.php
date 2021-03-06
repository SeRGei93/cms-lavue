<?php

namespace Modules\Menu\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Language\Entities\Language;
use Modules\Menu\Entities\Menu;

class MenuListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $languages = Language::pluck('name', 'id');

        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'position'     => Menu::positions()[$this->position],
            'show_img'     => $this->image ? asset('storage/' . Menu::FOLDER_IMG . '/' . key(Menu::getSizes()) . '/' . $this->image) : null,
            'show_page'    => generateRoute($this),
            'lang'         => $languages[$this->lang_id],
            'active'       => $this->active,
            'sort'         => $this->sort,
            'permissions' => [
                'edit'    => checkModulePermission('menu', 'edit'),
                'destroy' => checkModulePermission('menu', 'destroy')
            ]
        ];
    }
}
