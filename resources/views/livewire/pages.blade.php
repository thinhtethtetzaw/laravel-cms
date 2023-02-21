<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />

    <div class="p-6">
        <div class="flex items-center justify-end px-3 py-4 sm:px-6">
            <x-button wire:click='createShowModal'>
                {{ __('Create') }}
            </x-button>
        </div>
    
        {{-- Modal form  --}}
        <x-dialog-modal wire:model="modalFormVisible">
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>
    
            <x-slot name="content">
                <div class="mt-4">
                    <x-label for="title" value="{{ __('Title') }}" />
                    <x-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
                </div>
                <div class="mt-4">
                    <x-label for="slug" value="{{ __('Slug') }}" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            http://localhost:8000/
                        </span> 
                        <input wire:model="slug" class="form-input flex-1 block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300" placeholder="url-slug">
                    </div>
                </div>
                <div class="mt-4">
                    <x-label for="title" value="{{ __('Content') }}" />
                    <div class="rounded-md shadow-sm">
                        <div class="mt-1 bg-white">                      
                            <div class="body-content" wire:ignore>                            
                                <trix-editor
                                    class="trix-content"
                                    x-ref="trix"
                                    wire:model.debounce.100000ms="content"
                                    wire:key="trix-content-unique-key"
                                ></trix-editor>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
    
            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>
    
                <x-danger-button class="ml-3" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
</div>
