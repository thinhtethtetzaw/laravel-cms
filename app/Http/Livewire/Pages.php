<?php
namespace App\Http\Livewire;

use App\Models\Page;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Pages extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modelId;
    public $slug;
    public $title;
    public $content;
    public $isSetToDefaultHomePage;
    public $isSetToDefaultNotFoundPage;
    
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
        // $this->generateSlug($value);
        $this->slug = Str::slug($value); 
    }

    public function updatedIsSetToDefaultHomePage(){
         $this->isSetToDefaultNotFoundPage = null;
    }

    public function updatedIsSetToDefaultNotFoundPage(){
        $this->isSetToDefaultHomePage = null;
   }

    public function create(){
        $this->validate();
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFoundPage();
        Page::create($this->modelData());
        $this->modalFormVisible = false;
        $this->reset();
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
        $this->unassignDefaultHomePage();
        $this->unassignDefaultNotFoundPage();
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
        $this->reset();
        $this->modalFormVisible = true;
    }

    public function editShowModal($id){
        $this->resetValidation();
        $this->reset(); 
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
     * Load the old data of the page in the edit modal
     *
     * @return void
     */
    public function loadModel(){
        $data = Page::find($this->modelId);
        $this->title = $data->title;
        $this->slug = $data->slug;
        $this->content = $data->content;
        $this->isSetToDefaultHomePage = !$data->is_default_home ? null : true;
        $this->isSetToDefaultNotFoundPage = !$data->is_default_not_found ? null : true;
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
            'is_default_home' => $this->isSetToDefaultHomePage,
            'is_default_not_found'=> $this->isSetToDefaultNotFoundPage,
        ];
    }
    
    /**
     * result all the variables in modal to null.
     *
     * @return void
        
     */
    // this func doesn't need coz of laravel reset() 
    // public function resetVars(){
    //     $this->modelId = null;
    //     $this->title = null;
    //     $this->slug = null;
    //     $this->content = null;
    //     $this->isSetToDefaultHomePage = null;
    //     $this->isSetToDefaultNotFoundPage = null; 
    // }
    
    /**
     * generate url slug base on title.
     *
     * @param  mixed $value
     * @return void
     */

    // this func doesn't need coz Str::slug('') provided for generate slug  
    // private function generateSlug($value){
    //     $process1 = str_replace(' ', '-',$value);
    //     $process2 = strtolower($process1);
    //     $this->slug = $process2;
    // }

    // only one page will be home page and not found page
    private function unassignDefaultHomePage(){
        if($this->isSetToDefaultHomePage != null){
            Page::where('is_default_home',true)->update([
                'is_default_home' => false,
            ]);
        }
    }

    private function unassignDefaultNotFoundPage(){
        if($this->isSetToDefaultNotFoundPage != null){
            Page::where('is_default_not_found',true)->update([
                'is_default_not_found' => false,
            ]);
        }
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
