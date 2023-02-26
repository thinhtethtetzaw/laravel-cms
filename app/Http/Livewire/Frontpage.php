<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Page;

class Frontpage extends Component
{
    public $title;
    public $content;

    public function mount($urlslug = null){
        $this->retrieveContent($urlslug);
    }
    
    /**
     * retrieve the content of the page
     *
     * @param  mixed $urlslug
     * @return void
     */
    public function retrieveContent($urlslug){

        // get home page if slug is empty 
        if(empty($urlslug)){
            $data = Page::where('is_default_home',true)->first();
        }else{
            // get the page by the name of slug 
            $data = Page::where('slug',$urlslug)->first();  

            // if we type no slug value or sth wrong in url , get the 404 page
             if(!$data){
                $data = Page::where('is_default_not_found',true)->first();
             }
        }
         
        $this->title = $data->title;
        $this->content = $data->content;
    }

    public function render()
    {
        return view('livewire.frontpage')->layout('layouts.frontpage');
    }
}
