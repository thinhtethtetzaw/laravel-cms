<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />

    <div class="p-6">
        
        {{-- data table  --}}
        <div class="bg-white p-10 rounded-lg">
            <div class="flex items-center justify-end mb-5">
                <x-button wire:click='createShowModal' class="bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-500">
                    {{ __('Create') }}
                </x-button>
            </div>
            <div class="relative overflow-x-auto sm:rounded-lg border border-gray-200">
                <table class="w-full text-left text-gray-500">
                    <thead class="text-gray-700 uppercase bg-gray-50 text-sm border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Link
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Content
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data->count())
                            @foreach ($data as $item)
                                <tr class="bg-white border-b">
                                    <th class="px-6 py-4 font-normal">{{$item->id}}</th>
                                    <th class="px-6 py-4 font-normal">
                                        {{ $item-> title }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <a 
                                            target="_blank"
                                            href="{{ URL::to('/'.$item->slug) }}"
                                        >          
                                            {{ $item-> slug }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        {!! $item-> content !!}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-button wire:click='editShowModal({{ $item->id }})' class="bg-indigo-500 rounded-sm text-white hover:bg-indigo-600 focus:bg-indigo-500" style="padding: 5px 8px !important;">
                                            {{ __('Edit') }}
                                        </x-button>
                                        <x-button wire:click='deleteShowModal({{ $item->id }})' class="bg-red-500 rounded-sm text-white hover:bg-red-600" style="padding: 5px 8px !important;">
                                            {{ __('Delete') }}
                                        </x-button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap text-center" colspan='4'>No result</td>
                            </tr>
                        @endif
                        
                    </tbody>
                </table>
            </div>
            <br>   
            {{ $data->links() }}
        </div>

        {{-- Modal form  --}}
        <x-dialog-modal wire:model="modalFormVisible">
            @if ($modelId)
                <x-slot name="title">
                    {{ __('Edit Page') }} {{ $modelId }}
                </x-slot>    
            @else
                <x-slot name="title">
                    {{ __('Create Page') }}
                </x-slot>
            @endif
           
            <x-slot name="content">
                <div class="mt-4">
                    <x-label for="title" value="{{ __('Title') }}" />
                    <x-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
                    @error('title')
                        <span class="error">{{$message}}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <x-label for="slug" value="{{ __('Slug') }}" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            http://localhost:8000/
                        </span> 
                        <input wire:model="slug" class="form-input flex-1 block w-full rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-gray-300" placeholder="url-slug">
                    </div>
                    @error('slug')
                        <span class="error">{{$message}}</span>
                    @enderror
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
                    @error('content')
                        <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </x-slot>
    
            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('modalFormVisible')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                @if ($modelId)
                    <x-button class="ml-3 bg-indigo-600 hover:bg-indigo-600 focus:bg-indigo-500" wire:click="update">
                        {{ __('Update') }}
                    </x-button>
                @else     
                    <x-button class="ml-3 bg-indigo-600 hover:bg-indigo-600 focus:bg-indigo-500" wire:click="create">
                        {{ __('Create') }}
                    </x-button>
                @endif
    
            </x-slot>
        </x-dialog-modal>

        {{-- Delete modal  --}}
        <x-dialog-modal wire:model="modalConfirmDeleteVisible">
            <x-slot name="title">
                {{ __('Delete Page') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete your page? Once your page is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your page.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete Page') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
</div>
