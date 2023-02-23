<?php
namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Pages extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;
    
    /**
     * the validation rules
     *
     * @return void
     */
    public function rules(){
        return [
            'title' => 'required',
            'slug' => ['required', Rule::unique('pages','slug')->ignore($this->modelId)],
            'content' => 'required',
        ];
    }
    
    /**
     * the livewire mount function
     *
     * @return void
     */
    public function mount(){
        // reset the pagination after reloading the page
        $this->resetPage();
    }
    
    /**
     * run everytime the title variable is updated.
     *
     * @param  mixed $value
     * @return void
     */
    public function updatedTitle($value){
        $this->generateSlug($value);
    }

    public function create(){
        $this->validate();
        Page::create($this->modelData());
        $this->modalFormVisible = false;
        $this->resetVars();
    }

    public function read(){
        return Page::paginate(5);
    }
        
    /**
     * update function
     *
     * @return void
     */
    public function update(){
        $this->validate();
        Page::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function delete(){
        Page::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     * Show the form modal of the create function.
     *
     * @return void
     */
    public function createShowModal(){
        $this->resetValidation();
        $this->resetVars();
        $this->modalFormVisible = true;
    }

    public function editShowModal($id){
        $this->resetValidation();
        $this->resetVars(); 
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
    }
    
    /**
     * Show the delete confirmation modal of the delete function.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }
    
    /**
     * Load the model data of this component
     *
     * @return void
     */
    public function loadModel(){
        $data = Page::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
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
     * result all the variables in modal to null.
     *
     * @return void
        
     */
    public function resetVars(){
        $this->modelId = null;
        $this->title = null;
        $this->slug = null;
        $this->content = null;
    }
    
    /**
     * generate url slug base on title.
     *
     * @param  mixed $value
     * @return void
     */
    private function generateSlug($value){
        $process1 = str_replace(' ', '-',$value);
        $process2 = strtolower($process1);
        $this->slug = $process2;
    }
    
    /**
     * The live wire render function.
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.pages',[
            'data' => $this->read()
        ]);
    }
}
