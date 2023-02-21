<?php
namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;

class Pages extends Component
{
    public $modalFormVisible = false;
    public $slug;
    public $title;
    public $content;

    public function create(){
        Page::create($this->modelData());
        $this->modalFormVisible = false;
    }
    
    /**
     * Show the form modal of the create function.
     *
     * @return void
     */
    public function createShowModal(){
        $this->modalFormVisible = true;
    }
    
    /**
     * The data of the model mapped in this component.
     *
     * @return void
     */
    public function modelData(){
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
        ];
    }
    
    /**
     * The live wire render function.
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.pages');
    }
}
